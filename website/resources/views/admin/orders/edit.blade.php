@extends('layouts.admin')

@section('title', 'Edycja Zamówienia #'.$order->order_id)

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Edycja Zamówienia #{{ $order->order_id }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Formularz Edycji</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="order_id" class="form-label">ID Zamówienia</label>
                    <input type="text" class="form-control" id="order_id" value="#{{ $order->order_id }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="user_name" class="form-label">Klient</label>
                    <input type="text" class="form-control" id="user_name" value="{{ $order->user ? $order->user->name . ' ' . $order->user->surname . ' (' . $order->user->email . ')' : 'Użytkownik usunięty' }}" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="order_date" class="form-label">Data Zamówienia</label>
                    <input type="text" class="form-control" id="order_date" value="{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d H:i') : 'Brak' }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="total_price" class="form-label">Suma Zamówienia</label>
                    <input type="text" class="form-control" id="total_price" value="{{ number_format($order->total_price, 2, ',', ' ') }} zł" disabled> 
                    {{-- Możesz odkomentować, jeśli admin ma edytować cenę --}}
                    {{-- <input type="number" step="0.01" class="form-control" name="total_price" id="total_price" value="{{ old('total_price', $order->total_price) }}"> --}}
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status Zamówienia</label>
                    <select class="form-select" id="status" name="status" required>
                        @foreach($statuses as $statusValue)
                        <option value="{{ $statusValue }}" {{ old('status', $order->status) == $statusValue ? 'selected' : '' }}>
                            {{ ucfirst($statusValue) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Zaktualizuj Zamówienie</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Anuluj</a>
            </form>
        </div>
    </div>
</div>
@endsection