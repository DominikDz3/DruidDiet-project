<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class OrderController extends Controller
{
    public function __construct()
    {
    }

    
   
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('order_date', 'desc');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('email', 'like', '%' . $searchTerm . '%')
                                ->orWhere('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('surname', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        $statuses = ['oczekujące', 'w przygotowaniu', 'wysłane', 'zakończone', 'anulowane', 'zwrócone'];

        return view('admin.orders.manageorders', compact('orders', 'statuses')); //
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.catering', 'orderItems.diet']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // ZMIANA: Polskie statusy dla formularza edycji
        $statuses = ['oczekujące', 'w przygotowaniu', 'wysłane', 'zakończone', 'anulowane', 'zwrócone'];
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            // ZMIANA: Walidacja dla polskich statusów
            'status' => 'required|string|in:oczekujące,w przygotowaniu,wysłane,zakończone,anulowane,zwrócone',
        ]);

        try {
            $order->status = $request->status;
            $order->save();
            return redirect()->route('admin.orders.index')->with('success', 'Status zamówienia pomyślnie zaktualizowano.');
        } catch (\Exception $e) {
            Log::error('Błąd aktualizacji statusu zamówienia: ' . $e->getMessage());
            return back()->with('error', 'Wystąpił błąd podczas aktualizacji statusu zamówienia.');
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Zamówienie pomyślnie usunięto.');
        } catch (\Exception $e) {
            Log::error('Błąd usuwania zamówienia: ' . $e->getMessage());
            return back()->with('error', 'Wystąpił błąd podczas usuwania zamówienia.');
        }
    }
}