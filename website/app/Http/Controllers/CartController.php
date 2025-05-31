<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catering;
use App\Models\Diet;
use App\Models\Coupon; // <--- DODAJ IMPORT
use Illuminate\Support\Facades\Auth; // <--- DODAJ IMPORT
use Carbon\Carbon; // <--- DODAJ IMPORT

class CartController extends Controller
{
    // Metoda pomocnicza do obliczania sumy koszyka
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
            // Ponowna walidacja kuponu nie jest tu potrzebna, zakładamy, że dane w sesji są poprawne
            // Typ zniżki i wartość powinny być już w $appliedCouponData

            // Zakładamy, że discount_value to PROCENT (np. 0.15 dla 15%)
            // Jeśli masz pole 'discount_type' w modelu Coupon i przechowujesz je w sesji:
            // if ($appliedCouponData['discount_type'] === 'percentage') {
            //    $discountAmount = ($originalTotalPrice * $appliedCouponData['value']);
            // } elseif ($appliedCouponData['discount_type'] === 'fixed') {
            //    $discountAmount = $appliedCouponData['value'];
            // }
            // Dla uproszczenia i zgodnie z decimal(3,2) zakładamy procent:
            if (isset($appliedCouponData['value']) && is_numeric($appliedCouponData['value'])) {
                 $discountAmount = ($originalTotalPrice * $appliedCouponData['value']);
            }


            $finalTotalPrice = $originalTotalPrice - $discountAmount;
            if ($finalTotalPrice < 0) {
                $finalTotalPrice = 0;
                $discountAmount = $originalTotalPrice;
            }
        }

        return [
            'originalTotalPrice' => round($originalTotalPrice, 2),
            'discountAmount' => round($discountAmount, 2),
            'finalTotalPrice' => round($finalTotalPrice, 2),
        ];
    }

    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $appliedCoupon = $request->session()->get('applied_coupon');

        $totals = $this->calculateCartTotals($cart, $appliedCoupon);

        return view('cart.index', [
            'cart' => $cart,
            'originalTotalPrice' => $totals['originalTotalPrice'],
            'discountAmount' => $totals['discountAmount'],
            'finalTotalPrice' => $totals['finalTotalPrice'],
            // 'appliedCoupon' => $appliedCoupon // Nie jest już potrzebne, bo dane kuponu są w sesji i index je odczytuje
        ]);
    }

    public function add(Request $request)
    {
        // ... (Twoja istniejąca logika add - ważne, aby po modyfikacji koszyka wywołać revalidateCoupon) ...
        $request->validate([
            'product_id' => 'required|integer',
            'product_type' => 'required|string|in:catering,diet',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $productType = $request->input('product_type');
        $quantity = (int)$request->input('quantity');

        $product = null;
        if ($productType == 'catering') {
            $product = Catering::findOrFail($productId);
        } elseif ($productType == 'diet') {
            $product = Diet::findOrFail($productId);
        }

        if (!$product) {
            return back()->with('error', 'Nie znaleziono produktu.');
        }

        $cart = $request->session()->get('cart', []);
        $cartItemId = $productType . '_' . $product->{$productType.'_id'};

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $quantity;
        } else {
            $cart[$cartItemId] = [
                'id' => $product->{$productType.'_id'},
                'type' => $productType,
                'name' => $product->title,
                'price' => $product->price,
                'quantity' => $quantity,
                'photo' => $product->photo,
            ];
        }
        $request->session()->put('cart', $cart);
        $this->revalidateCoupon($request); // <--- REWALIDACJA KUPONU

        return back()->with('success', 'Dodano produkt do koszyka!');
    }

    public function update(Request $request, $cartItemId)
    {
        // ... (Twoja istniejąca logika update - ważne, aby po modyfikacji koszyka wywołać revalidateCoupon) ...
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] = (int)$request->input('quantity');
            $request->session()->put('cart', $cart);
            $this->revalidateCoupon($request); // <--- REWALIDACJA KUPONU
            return back()->with('success', 'Ilość produktu w koszyku zaktualizowana.');
        }
        return back()->with('error', 'Nie znaleziono produktu w koszyku.');
    }

    public function remove(Request $request, $cartItemId)
    {
        // ... (Twoja istniejąca logika remove - ważne, aby po modyfikacji koszyka wywołać revalidateCoupon) ...
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            $request->session()->put('cart', $cart);
            $this->revalidateCoupon($request); // <--- REWALIDACJA KUPONU
            return back()->with('success', 'Produkt usunięty z koszyka.');
        }
        return back()->with('error', 'Nie znaleziono produktu w koszyku.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');
        $request->session()->forget('applied_coupon');
        return back()->with('success', 'Koszyk został wyczyszczony.');
    }


    public function validateCouponLogic($coupon, $originalCartTotal)
    {
        if (!$coupon) {
            return 'Nieprawidłowy kod kuponu.';
        }
        if ($coupon->is_used) {
            return 'Ten kupon został już wykorzystany.';
        }
        if ($coupon->user_id && $coupon->user_id !== Auth::id()) {
            return 'Ten kupon nie jest przypisany do Twojego konta.';
        }
        return null;
    }

    // Prywatna metoda do rewalidacji kuponu po zmianie koszyka
    private function revalidateCoupon(Request $request) {
        $appliedCouponData = $request->session()->get('applied_coupon');
        if (!$appliedCouponData) {
            return; // Brak kuponu do rewalidacji
        }

        $cart = $request->session()->get('cart', []);
        $totals = $this->calculateCartTotals($cart); // Oblicz sumę bez kuponu

        $coupon = Coupon::where('code', $appliedCouponData['code'])->first();
        $errorMessage = $this->validateCouponLogic($coupon, $totals['originalTotalPrice']);

        if ($errorMessage) {
            $request->session()->forget('applied_coupon');
            // Możesz dodać flash message, że kupon został usunięty z powodu zmiany zawartości koszyka
            $request->session()->flash('coupon_error', 'Zastosowany kupon (' . $appliedCouponData['code'] . ') został usunięty, ponieważ warunki jego użycia przestały być spełnione.');
        }
        // Jeśli kupon nadal jest ważny, pozostaje w sesji.
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string|max:50']); // Dodano max
        $couponCode = trim($request->input('coupon_code'));

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('coupon_error', 'Dodaj produkty do koszyka przed zastosowaniem kuponu.');
        }

        $totals = $this->calculateCartTotals($cart); // Oblicz sumę przed rabatem
        $originalTotalPrice = $totals['originalTotalPrice'];

        $coupon = Coupon::where('code', $couponCode)->first();

        $errorMessage = $this->validateCouponLogic($coupon, $originalTotalPrice);
        if ($errorMessage) {
            return back()->with('coupon_error', $errorMessage);
        }


        $request->session()->put('applied_coupon', [
            'id' => $coupon->coupon_id, // Ważne, aby móc go oznaczyć jako użyty
            'code' => $coupon->code,
            'value' => $coupon->discount_value, 
        ]);

        return back()->with('coupon_success', 'Kod rabatowy "' . $coupon->code . '" został pomyślnie zastosowany!');
    }

    public function removeCoupon(Request $request)
    {
        $request->session()->forget('applied_coupon');
        return back()->with('success', 'Kod rabatowy został usunięty.');
    }
}
