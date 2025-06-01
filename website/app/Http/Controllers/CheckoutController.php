<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Catering;
use App\Models\Diet;
use App\Models\User;
use App\Models\Coupon; // <--- DODAJ IMPORT
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Metoda pomocnicza do obliczania sumy koszyka (może być zduplikowana z CartController lub przeniesiona do serwisu)
    private function calculateCartTotals(array $cart, $appliedCouponData = null)
    {
        $originalTotalPrice = 0;
        foreach ($cart as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $originalTotalPrice += $item['price'] * $item['quantity'];
            }
        }

        $discountAmount = 0;
        $finalTotalPrice = $originalTotalPrice;

        if ($appliedCouponData && isset($appliedCouponData['code'])) {
            // Zakładamy, że discount_value to PROCENT (np. 0.15 dla 15%)
            if (isset($appliedCouponData['value']) && is_numeric($appliedCouponData['value'])) {
                 $discountAmount = ($originalTotalPrice * $appliedCouponData['value']);
            }
            $finalTotalPrice = $originalTotalPrice - $discountAmount;
            if ($finalTotalPrice < 0) $finalTotalPrice = 0;
        }
        return [
            'originalTotalPrice' => round($originalTotalPrice, 2),
            'discountAmount' => round($discountAmount, 2),
            'finalTotalPrice' => round($finalTotalPrice, 2),
        ];
    }

    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Twój koszyk jest pusty! Nie można złożyć zamówienia.');
        }

        $user = Auth::user();
        $verifiedCartItems = [];
        $originalCartPrice = 0;

        foreach ($cart as $cartItemId => $itemDetails) {
            $product = null;
            if (!isset($itemDetails['type'], $itemDetails['id'], $itemDetails['quantity'], $itemDetails['name'])) {
                Log::warning('Niekompletne dane dla pozycji w koszyku (Checkout - ID sesji: ' . $cartItemId . ')', ['item' => $itemDetails, 'user_id' => $user->user_id]);
                unset($cart[$cartItemId]);
                continue;
            }

            if ($itemDetails['type'] === 'catering') $product = Catering::find($itemDetails['id']);
            elseif ($itemDetails['type'] === 'diet') $product = Diet::find($itemDetails['id']);

            if (!$product) {
                $request->session()->put('cart', $cart);
                return redirect()->route('cart.index')->with('error', 'Produkt "' . $itemDetails['name'] . '" nie jest już dostępny. Sprawdź koszyk.');
            }
            $originalCartPrice += $product->price * $itemDetails['quantity'];
            $verifiedCartItems[$cartItemId] = [
                'product_instance' => $product,
                'type' => $itemDetails['type'],
                'quantity' => $itemDetails['quantity'],
                'price_per_item' => $product->price
            ];
        }




        $appliedCouponData = $request->session()->get('applied_coupon');
        $finalOrderPrice = $originalCartPrice;
        $couponToMarkAsUsed = null;

        if ($appliedCouponData) {
            $coupon = Coupon::find($appliedCouponData['id']);
            $errorMessage = app(CartController::class)->validateCouponLogic($coupon, $originalCartPrice);

            if (!$errorMessage && $coupon) {
                $discountValue = $coupon->discount_value; // Zakładamy procent np. 0.15
                $discountAmount = ($originalCartPrice * $discountValue);
                $finalOrderPrice = $originalCartPrice - $discountAmount;
                if ($finalOrderPrice < 0) $finalOrderPrice = 0;

                $couponToMarkAsUsed = $coupon;
            } else {
                // Kupon stał się nieprawidłowy lub nie istnieje
                $request->session()->forget('applied_coupon');
                return redirect()->route('cart.index')->with('coupon_error', $errorMessage ?: 'Zastosowany kupon rabatowy jest już nieprawidłowy. Spróbuj ponownie.');
            }
        }


        $paymentMethod = 'cash';
        $pointsUsed = 0;
        $pointsToEarn = 0;
        $pointsNeededForOrder = (int)round($finalOrderPrice);

        if ($request->input('pay_with_points') == '1') {
            if ($user->loyalty_points >= $pointsNeededForOrder && $finalOrderPrice > 0) {
                $paymentMethod = 'loyalty_points';
                $pointsUsed = $pointsNeededForOrder;
            } elseif ($finalOrderPrice == 0 && $couponToMarkAsUsed) { // Zamówienie darmowe dzięki kuponowi
                 $paymentMethod = 'coupon_discount'; // Specjalny status dla darmowego zamówienia po kuponie
                 $pointsUsed = 0; // Nie użyto punktów
            }
             else {
                return redirect()->route('cart.index')->with('error', 'Nie masz wystarczającej liczby punktów lub wystąpił problem z płatnością punktami.');
            }
        }

        // Punkty przyznawane tylko jeśli nie płacono punktami i nie jest to darmowe zamówienie po kuponie
        if ($paymentMethod === 'cash') {
            $pointsToEarn = (int)round($finalOrderPrice * 0.30); // 30% wartości FINALNEJ zamówienia
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->user_id,
                'order_date' => Carbon::now(),
                'total_price' => $finalOrderPrice, // Zapisujemy cenę po wszystkich zniżkach
                'status' => 'oczekujące',
                'payment_method' => $paymentMethod,
                'points_used' => $pointsUsed,
                'points_earned' => $pointsToEarn,
                'coupon_id' => $couponToMarkAsUsed ? $couponToMarkAsUsed->coupon_id : null,
                'applied_coupon_code' => $couponToMarkAsUsed ? $couponToMarkAsUsed->code : null,

            ]);

            foreach ($verifiedCartItems as $itemData) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'catering_id' => ($itemData['type'] === 'catering') ? $itemData['product_instance']->catering_id : null,
                    'diet_id' => ($itemData['type'] === 'diet') ? $itemData['product_instance']->diet_id : null,
                    'quantity' => $itemData['quantity'],
                    'price_per_item' => $itemData['price_per_item'],
                ]);
            }

            // Aktualizacja punktów użytkownika i statusu kuponu
            if ($paymentMethod === 'loyalty_points') {
                $user->loyalty_points -= $pointsUsed;
            } elseif ($paymentMethod === 'cash') {
                $user->loyalty_points += $pointsToEarn;
            }
            $user->save();

            if ($couponToMarkAsUsed && $paymentMethod !== 'loyalty_points') { // Oznaczenie kuponu jako użyty, jeśli nie płacono punktami
                $couponToMarkAsUsed->is_used = true;
                $couponToMarkAsUsed->save();
            }

            DB::commit();
            $request->session()->forget('cart');
            $request->session()->forget('applied_coupon');

            // ... (komunikat sukcesu)
             $successMessage = 'Twoje zamówienie (numer: #' . $order->order_id . ') zostało pomyślnie złożone!';
            if ($paymentMethod === 'cash' && $pointsToEarn > 0) {
                $successMessage .= ' Otrzymałeś ' . $pointsToEarn . ' punktów lojalnościowych.';
            } elseif ($paymentMethod === 'loyalty_points') {
                $successMessage .= ' Zostało opłacone punktami.';
            } elseif ($couponToMarkAsUsed) {
                $successMessage .= ' Zastosowano kod rabatowy: ' . $couponToMarkAsUsed->code . '.';
            }


            return redirect()->route('user.orders.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            // ... (obsługa błędu)
            DB::rollBack();
            Log::error('Krytyczny błąd podczas składania zamówienia (Coupons & Loyalty): ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart_on_exception' => $request->session()->get('cart', []),
                'exception_trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart.index')->with('error', 'Wystąpił nieoczekiwany błąd. Spróbuj ponownie lub skontaktuj się z obsługą.');
        }
    }
}
