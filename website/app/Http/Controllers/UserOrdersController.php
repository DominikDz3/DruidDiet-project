<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserOrdersController extends Controller 
{
    /**
     * Display a listing of the user's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        // Zakładamy, że relacja orders() w modelu User jest poprawnie zdefiniowana
        $orders = $user->orders()->orderBy('order_date', 'desc')->paginate(10);

        // Używamy ścieżki widoku, którą podałeś: 'user.orders'
        return view('user.orders', compact('orders'));
    }

    public function show(Order $order) // Pamiętaj, żeby tu też zmienić typ, jeśli będzie potrzebne
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('user.order-details', compact('order')); // Przykładowa inna ścieżka dla szczegółów
    }
}