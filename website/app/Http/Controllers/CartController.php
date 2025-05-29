<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catering;
use App\Models\Diet;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        return view('cart.index', compact('cart', 'totalPrice'));
    }

    public function add(Request $request)
    {
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

        return back()->with('success', 'Dodano produkt do koszyka!');
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);
        $quantity = (int)$request->input('quantity');

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
            return back()->with('success', 'Ilość produktu w koszyku zaktualizowana.');
        }

        return back()->with('error', 'Nie znaleziono produktu w koszyku.');
    }

    public function remove(Request $request, $cartItemId)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            $request->session()->put('cart', $cart);
            return back()->with('success', 'Produkt usunięty z koszyka.');
        }

        return back()->with('error', 'Nie znaleziono produktu w koszyku.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');
        return back()->with('success', 'Koszyk został wyczyszczony.');
    }
}