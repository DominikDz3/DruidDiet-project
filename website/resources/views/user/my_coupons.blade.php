@extends('layouts.app') 

@section('title', 'Moje Kody Rabatowe - ' . config('app.name'))

@section('content')
<section class="user-dashboard py-5 container">
    <div class="row">
        <aside class="col-md-3 mb-4">
            <div class="list-group rounded-3 overflow-hidden">
                <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active-custom' : '' }}">
                    <i class="bi bi-person-circle me-2"></i> Mój Profil
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
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-light py-3">
                    <h4 class="mb-0 fw-bold" style="color: #4a6b5a;">Moje Kody Rabatowe</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($coupons->isNotEmpty())
                        <p class="text-muted">Poniżej znajdują się Twoje aktywne, niewykorzystane kody rabatowe. Możesz je wykorzystać podczas składania zamówienia.</p>
                        <div class="list-group mt-3">
                            @foreach($coupons as $coupon)
                                <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border rounded-3 shadow-sm p-3"> {{-- Dodano p-3 dla paddingu --}}
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h5 class="mb-1 fw-bold" style="color: #4a6b5a;">
                                            <i class="bi bi-tag-fill me-2"></i>Kod: {{ $coupon->code }}
                                        </h5>
                                        <span class="badge bg-success rounded-pill">Aktywny</span>
                                    </div>
                                    <p class="mb-1 mt-2">
                                        <strong>Zniżka:</strong> {{ $coupon->discount_value * 100 }}%
                                    </p>
                                    <small class="text-muted">
                                        Dodano: {{ \Carbon\Carbon::parse($coupon->created_at)->format('d F Y') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center mt-3" role="alert">
                            <i class="bi bi-ticket-detailed fs-2 mb-2 d-block"></i>
                            Nie masz obecnie żadnych aktywnych kodów rabatowych.
                            <br>
                            <small class="text-muted">Kody rabatowe mogą pojawić się tutaj jako nagrody lub w ramach promocji.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection