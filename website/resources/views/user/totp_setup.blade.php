@extends('layouts.app')

@section('title', 'Konfiguracja Uwierzytelniania Dwuskładnikowego - ' . config('app.name'))

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
                <a href="{{ route('user.totp.setup') }}" class="list-group-item list-group-item-action active-custom">
                    <i class="bi bi-shield-lock me-2"></i> Uwierzytelnianie 2FA
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i> Ustawienia konta
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
                    <h4 class="mb-0 fw-bold" style="color: #4a6b5a;">Konfiguracja Uwierzytelniania Dwuskładnikowego (TOTP)</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p>Aby włączyć uwierzytelnianie dwuskładnikowe, wprowadź poniższy klucz konfiguracyjny (secret key) do swojej aplikacji uwierzytelniającej (np. Google Authenticator, Authy, Microsoft Authenticator) wybierając opcję ręcznego dodania konta lub "wprowadź klucz konfiguracyjny".</p>

                    <div class="alert alert-info text-center my-4">
                        <p class="mb-1">Twój klucz konfiguracyjny (Secret Key):</p>
                        <strong style="font-family: monospace; font-size: 1.2rem; color: #0c5460;">{{ $secret }}</strong>
                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $secret }}')" title="Kopiuj klucz">
                            <i class="bi bi-clipboard"></i> Kopiuj
                        </button>
                    </div>
                    
                    <p class="text-muted small">Aplikacja uwierzytelniająca powinna automatycznie wykryć typ kodu (TOTP), interwał (30 sekund) i liczbę cyfr (6). Jako nazwę konta możesz podać np. "{{ config('app.name') }} ({{ Auth::user()->email }})".</p>

                    <hr class="my-4">

                    <p>Po dodaniu klucza do aplikacji, wprowadź wygenerowany przez nią 6-cyfrowy kod, aby zakończyć konfigurację i włączyć TOTP.</p>

                    <form method="POST" action="{{ route('user.totp.enable') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="totp_code" class="form-label">Kod Weryfikacyjny TOTP (6 cyfr): <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('totp_code') is-invalid @enderror" 
                                   id="totp_code" 
                                   name="totp_code" 
                                   required 
                                   pattern="\d{6}" 
                                   maxlength="6" 
                                   inputmode="numeric"
                                   autocomplete="one-time-code"
                                   placeholder="Wpisz kod z aplikacji">
                            @error('totp_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: #4a6b5a; border-color: #4f772d;">
                            <i class="bi bi-check-circle me-2"></i> Weryfikuj i Włącz TOTP
                        </button>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary ms-2">Anuluj</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/totp.js') }}" defer></script>
@endpush