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

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Wystąpiły błędy:</strong>
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
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Nazwisko:</strong>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $user->surname }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Email:</strong>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <strong class="col-sm-3 col-form-label">Alergeny:</strong>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $user->allergens ?: 'Nie podano' }}</p>
                            </div>
                        </div>
                         <p class="text-muted small mt-4">Aby zmienić hasło, kliknij "Edytuj Profil".</p>
                    </div>

                    <div id="profileEditFormSection" style="display: none;" @if($errors->any()) data-has-errors="true" @else data-has-errors="false" @endif>
                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Imię <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name', 'updateProfile') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name', 'updateProfile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="surname" class="form-label">Nazwisko <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('surname', 'updateProfile') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                                    @error('surname', 'updateProfile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adres Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email', 'updateProfile') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email', 'updateProfile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <p class="text-muted small">Zmień hasło (pozostaw puste, jeśli nie chcesz zmieniać):</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Nowe Hasło</label>
                                    <input type="password" class="form-control @error('password', 'updateProfile') is-invalid @enderror" id="password" name="password">
                                    @error('password', 'updateProfile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Potwierdź Nowe Hasło</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label for="allergens" class="form-label">Twoje alergeny (oddzielone przecinkami)</label>
                                <textarea class="form-control @error('allergens', 'updateProfile') is-invalid @enderror" id="allergens" name="allergens" rows="3">{{ old('allergens', $user->allergens) }}</textarea>
                                @error('allergens', 'updateProfile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" id="cancelEditBtn" class="btn btn-outline-secondary me-2">Anuluj</button>
                                <button type="submit" class="btn btn-primary" style="background-color: #4a6b5a !important; border-color: #4f772d !important;">Zapisz zmiany</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/profile-edit.js') }}" defer></script>
@endpush