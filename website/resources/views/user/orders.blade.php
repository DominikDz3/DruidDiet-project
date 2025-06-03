@extends('layouts.app')

@section('title', 'Moje Zamówienia - DruidDiet')

@section('content')
    <section class="user-dashboard py-5 container">
        <div class="row">
            <aside class="col-md-3 mb-4">
                <div class="list-group rounded-3 overflow-hidden">
                    <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active-custom' : '' }}">
                        <i class="bi bi-person-circle me-2"></i> Konto
                    </a>
                    <a href="{{ route('user.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.orders.index') ? 'active-custom' : '' }}">
                        <i class="bi bi-basket me-2"></i> Zamówienia
                    </a>
                    <a href="{{ route('user.myCoupons') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.myCoupons') ? 'active-custom' : '' }}">
                    <i class="bi bi-tags me-2"></i> Moje Kody Rabatowe
                    </a>
                    <a href="{{ route('calculators.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('calculators.*') ? 'active-custom' : '' }}">
                        <i class="bi bi-calculator me-2"></i> Kalkulatory
                    </a>
                     <a href="{{ route('user.totp.manage') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.totp.manage') || request()->routeIs('user.totp.setup') ? 'active-custom' : '' }}">
                    <i class="bi bi-shield-lock me-2"></i> Uwierzytelnianie 2FA
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Wyloguj się
                        </button>
                    </form>
                </div>
            </aside>

            <div class="col-md-9">
                <div class="p-4 bg-white rounded-3 shadow-sm">
                    <h2 class="fw-bold mb-4">Moje Zamówienia</h2>

                    @if($orders->isEmpty())
                        <p>Nie masz jeszcze żadnych zamówień.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID Zamówienia</th>
                                        <th scope="col">Data Zamówienia</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Suma</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->order_id }}</td>
                                            <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d.m.Y H:i') : 'Brak daty' }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($order->status == 'pending') bg-warning text-dark
                                                    @elseif($order->status == 'processing') bg-info text-dark
                                                    @elseif($order->status == 'completed') bg-success
                                                    @elseif($order->status == 'cancelled') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($order->total_price, 2, ',', ' ') }} zł</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links() }} {{-- Paginacja --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .active-custom {
        color: #fff;
        background-color: #198754; 
        border-color: #198754;
    }
    .page-item.active .page-link {
        background-color: #198754;
        border-color: #198754;
    }
    .page-link {
        color: #198754;
    }
    .page-link:hover {
        color: #105c38;
    }


    table th,
    .table th,
    .table > thead > tr > th {
        font-weight: normal;
    }
</style>
@endpush