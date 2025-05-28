@extends('layouts.app')

@section('title', 'Mój Profil - ' . config('app.name'))

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
            <div class="card shadow-sm rounded-3 mb-4">
                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold" style="color: #4a6b5a;">Twój Profil</h4>
                    <button id="toggleEditProfileBtn" class="btn btn-sm btn-outline-primary" style="border-color: #4a6b5a; color: #4a6b5a;">
                        <i class="bi bi-pencil-square me-1"></i> Edytuj Profil
                    </button>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any() && !$errors->has('token_f12_error') && !$errors->has('token_f12') && !$errors->has('password_f12'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Wystąpiły błędy podczas aktualizacji profilu:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div id="profileDisplaySection">
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Imię:</strong>
                            <div class="col-sm-9"><p class="form-control-plaintext">{{ $user->name }}</p></div>
                        </div>
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Nazwisko:</strong>
                            <div class="col-sm-9"><p class="form-control-plaintext">{{ $user->surname }}</p></div>
                        </div>
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Email:</strong>
                            <div class="col-sm-9"><p class="form-control-plaintext">{{ $user->email }}</p></div>
                        </div>
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Alergeny:</strong>
                            <div class="col-sm-9"><p class="form-control-plaintext">{{ $user->allergens ?: 'Nie podano' }}</p></div>
                        </div>
                         <p class="text-muted small mt-4">Aby zmienić dane lub hasło w standardowy sposób, kliknij "Edytuj Profil".</p>
                    </div>

                    <div id="profileEditFormSection" style="display: none;" @if($errors->any() && !$errors->has('token_f12_error') && !$errors->has('token_f12') && !$errors->has('password_f12')) data-has-errors="true" @else data-has-errors="false" @endif>
                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Imię <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="surname" class="form-label">Nazwisko <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                                    @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Adres Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <hr class="my-4">
                            <div class="mb-3">
                                <label for="allergens" class="form-label">Twoje alergeny (oddzielone przecinkami)</label>
                                <textarea class="form-control @error('allergens') is-invalid @enderror" id="allergens" name="allergens" rows="3">{{ old('allergens', $user->allergens) }}</textarea>
                                @error('allergens') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" id="cancelEditBtn" class="btn btn-outline-secondary me-2">Anuluj</button>
                                <button type="submit" class="btn btn-primary" style="background-color: #4a6b5a !important; border-color: #4f772d !important;">Zapisz zmiany</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm rounded-3 mt-4">
                <div class="card-header bg-light py-3">
                    <h4 class="mb-0 fw-bold" style="color: #4a6b5a;">Zmień Hasło za Pomocą Tokenu F12</h4>
                </div>
                <div class="card-body p-4" id="f12TokenDataHolder"
                    @if (session('token_for_f12_console'))
                        data-f12-token="{{ session('token_for_f12_console') }}"
                    @endif
                >
                    @if (session('f12_token_info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('f12_token_info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.generateTokenF12') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-1"></i> Wygeneruj Token (sprawdź konsolę F12 po odświeżeniu)
                        </button>
                    </form>

                    <hr>
                    <p class="text-muted">Po wygenerowaniu i skopiowaniu tokenu z konsoli F12, użyj poniższego formularza, aby ustawić nowe hasło.</p>

                    @if (session('success_password_f12'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_password_f12') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->has('token_f12_error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('token_f12_error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.updatePasswordF12') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="token_f12" class="form-label">Token z konsoli F12 <span class="text-danger">*</span></label>
                            <input id="token_f12" type="text" class="form-control @error('token_f12') is-invalid @enderror" name="token_f12" value="{{ old('token_f12') }}" required>
                            @error('token_f12')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_f12" class="form-label">Nowe Hasło <span class="text-danger">*</span></label>
                            <input id="password_f12" type="password" class="form-control @error('password_f12') is-invalid @enderror" name="password_f12" required autocomplete="new-password">
                            @error('password_f12')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_f12_confirmation" class="form-label">Potwierdź Nowe Hasło <span class="text-danger">*</span></label>
                            <input id="password_f12_confirmation" type="password" class="form-control" name="password_f12_confirmation" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary" style="background-color: #4a6b5a !important; border-color: #4f772d !important;">
                            <i class="bi bi-shield-check me-1"></i> Zmień Hasło z Tokenem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/profile-edit.js') }}" defer></script>
<script src="{{ asset('js/f12-token-logger.js') }}" defer></script>
@endpush