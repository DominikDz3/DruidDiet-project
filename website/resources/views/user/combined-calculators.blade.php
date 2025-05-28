@extends('layouts.app')

@section('title', 'Kalkulatory - ' . config('app.name'))

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
                <div class="card-header bg-light py-3">
                    <h4 class="mb-0 fw-bold" style="color: #4a6b5a;">Twoje Kalkulatory Zdrowotne</h4>
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

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 {{ isset($activeCalculator) && $activeCalculator === 'water' ? 'border border-primary' : '' }}"> {{-- Usunięto shadow-sm i rounded-3 bo już są na karcie nadrzędnej, dodałem 'h-100' dla równej wysokości --}}
                                <div class="card-header py-3">
                                    <h5 class="mb-0 fw-bold">Zapotrzebowanie na wodę</h5>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST" action="{{ route('calculators.water.calculator.calculate') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="water_weight" class="form-label">Twoja waga (kg):</label>
                                            <input type="number" step="0.1" class="form-control @error('weight') is-invalid @enderror" id="water_weight" name="weight" value="{{ old('weight', $waterWeight ?? '') }}" required>
                                            @error('weight')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="water_activity_level" class="form-label">Poziom aktywności:</label>
                                            <select class="form-select @error('activity_level') is-invalid @enderror" id="water_activity_level" name="activity_level" required>
                                                <option value="" disabled selected>Wybierz poziom aktywności</option>
                                                <option value="1.0" {{ old('activity_level', $waterActivityLevel ?? '') == '1.0' ? 'selected' : '' }}>Niska (siedzący tryb życia)</option>
                                                <option value="1.2" {{ old('activity_level', $waterActivityLevel ?? '') == '1.2' ? 'selected' : '' }}>Umiarkowana (umiarkowane ćwiczenia)</option>
                                                <option value="1.4" {{ old('activity_level', $waterActivityLevel ?? '') == '1.4' ? 'selected' : '' }}>Wysoka (intensywne treningi)</option>
                                            </select>
                                            @error('activity_level')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary" style="background-color: #4a6b5a !important; border-color: #4f772d !important;">Oblicz zapotrzebowanie</button>
                                    </form>

                                    @isset($waterNeeded)
                                        <hr class="my-4">
                                        <div class="alert alert-success mt-3" role="alert">
                                            Szacowane dzienne zapotrzebowanie na wodę: <strong>{{ $waterNeeded }} litra/litrów</strong>.
                                        </div>
                                    @endisset
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- @push('scripts')
<script src="{{ asset('js/profile-edit.js') }}" defer></script>
@endpush --}}