@extends('layouts.admin')

@section('title', 'Szczegóły Zamówienia #'.$order->order_id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Szczegóły Zamówienia #{{ $order->order_id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Powrót do listy</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Informacje o Zamówieniu</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Zamówienia:</strong> #{{ $order->order_id }}</p>
                    <p><strong>Data Zamówienia:</strong> {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d H:i') : 'Brak' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ match($order->status) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'completed', 'shipped' => 'success',
                                    'cancelled', 'refunded' => 'danger',
                                    default => 'secondary'
                                } }} text-dark">{{ ucfirst($order->status) }}</span></p>
                    <p><strong>Suma Zamówienia:</strong> {{ number_format($order->total_price, 2, ',', ' ') }} zł</p>
                </div>
                <div class="col-md-6">
                    @if($order->user)
                    <p><strong>Klient:</strong> {{ $order->user->name }} {{ $order->user->surname }}</p>
                    <p><strong>Email Klienta:</strong> {{ $order->user->email }}</p>
                    @else
                    <p><strong>Klient:</strong> Użytkownik usunięty</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Pozycje Zamówienia</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Typ</th>
                            <th>Ilość</th>
                            <th>Cena za szt.</th>
                            <th>Suma częściowa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->orderItems as $item)
                        <tr>
                            <td>
                                @if($item->catering)
                                    {{ $item->catering->title }}
                                @elseif($item->diet)
                                    {{ $item->diet->title }}
                                @else
                                    Produkt usunięty
                                @endif
                            </td>
                            <td>
                                @if($item->catering_id) Catering @elseif($item->diet_id) Dieta @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price_per_item, 2, ',', ' ') }} zł</td>
                            <td>{{ number_format($item->quantity * $item->price_per_item, 2, ',', ' ') }} zł</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Brak pozycji w zamówieniu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
     <div class="mt-3">
        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">Edytuj Zamówienie</a>
    </div>
</div>
@endsection