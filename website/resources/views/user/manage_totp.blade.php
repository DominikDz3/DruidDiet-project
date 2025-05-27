@extends('layouts.app')

@section('title', 'Uwierzytelnianie Dwuskładnikowe (2FA) - ' . config('app.name'))

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
                <a href="{{ route('user.totp.manage') }}" class="list-group-item list-group-item-action active-custom"> {{-- Ten link powinien być teraz aktywny --}}
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
                    <h4 class="mb-0 fw-bold" style="color: #4a6b5a;">Zarządzaj Uwierzytelnianiem Dwuskładnikowym (2FA)</h4>
                </div>
                <div class="card-body p-4">
                    @include('partials.alerts')

                    @if($user->TOTP_secret)
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-shield-check-fill fs-4 me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Uwierzytelnianie dwuskładnikowe (TOTP) jest aktywne.</h5>
                                Twoje konto jest dodatkowo chronione.
                            </div>
                        </div>
                        <p class="mt-3">Jeśli chcesz wyłączyć uwierzytelnianie dwuskładnikowe, potwierdź operację swoim hasłem.</p>

                        <form method="POST" action="{{ route('user.totp.disable') }}" id="disableTotpForm" class="mt-3 border p-3 rounded bg-light">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password_for_disable_totp" class="form-label fw-semibold">Potwierdź hasłem, aby wyłączyć 2FA:</label>
                                <input type="password"
                                       class="form-control @if(session('error_disable_totp') || $errors->has('current_password_for_disable_totp')) is-invalid @endif"
                                       id="current_password_for_disable_totp"
                                       name="current_password_for_disable_totp" required>
                                @if(session('error_disable_totp'))
                                    <div class="invalid-feedback d-block">
                                        {{ session('error_disable_totp') }}
                                    </div>
                                @endif
                                @error('current_password_for_disable_totp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="button" class="btn btn-danger" onclick="confirmDisableTotp()">
                                <i class="bi bi-shield-slash me-2"></i> Wyłącz 2FA
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                             <i class="bi bi-shield-exclamation-fill fs-4 me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Uwierzytelnianie dwuskładnikowe (TOTP) jest nieaktywne.</h5>
                                Zalecamy włączenie tej funkcji, aby zwiększyć bezpieczeństwo swojego konta.
                            </div>
                        </div>
                        <a href="{{ route('user.totp.setup') }}" class="btn btn-primary mt-3" style="background-color: #4a6b5a; border-color: #4f772d;">
                            <i class="bi bi-shield-lock-fill me-2"></i> Skonfiguruj i Włącz 2FA
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@if(Auth::user()->TOTP_secret)
<script src="{{ asset('js/disable-totp.js') }}" defer></script>
@endif
@endpush
