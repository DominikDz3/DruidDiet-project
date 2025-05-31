<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Catering;
use App\Models\Diet;     
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Tylko zalogowani użytkownicy mogą złożyć zamówienie
    }

    /**
     * Przetwarza koszyk i tworzy zamówienie.
     */
    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Twój koszyk jest pusty!');
        }

        $user = Auth::user();
        $totalPrice = 0;


        foreach ($cart as $cartItemId => $itemDetails) {
            $product = null;
            if ($itemDetails['type'] === 'catering') {
                $product = Catering::find($itemDetails['id']);
            } elseif ($itemDetails['type'] === 'diet') {
                $product = Diet::find($itemDetails['id']);
            }

            if ($product) {
                $totalPrice += $product->price * $itemDetails['quantity'];
            } else {
                unset($cart[$cartItemId]);
                $request->session()->put('cart', $cart); //
                return redirect()->route('cart.index')->with('error', 'Jeden z produktów w Twoim koszyku nie jest już dostępny. Sprawdź koszyk ponownie.');
            }
        }

        if ($totalPrice <= 0 && count($cart) > 0) {
             return redirect()->route('cart.index')->with('error', 'Nie można przetworzyć zamówienia z zerową lub ujemną wartością. Skontaktuj się z obsługą.');
        }
        if (empty($cart)) {
             return redirect()->route('cart.index')->with('error', 'Żaden z produktów w Twoim koszyku nie jest już dostępny.');
        }



        $order = Order::create([
            'user_id' => $user->user_id,
            'order_date' => Carbon::now(),
            'total_price' => $totalPrice,
            'status' => 'oczekujące',
        ]);


        foreach ($cart as $cartItemId => $itemDetails) {
            $product = null;
             if ($itemDetails['type'] === 'catering') {
                $product = Catering::find($itemDetails['id']);
            } elseif ($itemDetails['type'] === 'diet') {
                $product = Diet::find($itemDetails['id']);
            }


            if ($product) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'catering_id' => ($itemDetails['type'] === 'catering') ? $itemDetails['id'] : null,
                    'diet_id' => ($itemDetails['type'] === 'diet') ? $itemDetails['id'] : null,
                    'quantity' => $itemDetails['quantity'],
                    'price_per_item' => $product->price,
                ]);
            }
        }

        $request->session()->forget('cart');


        return redirect()->route('user.orders.index')->with('success', 'Twoje zamówienie zostało pomyślnie złożone! Numer Twojego zamówienia to: #' . $order->order_id);
    }
}
