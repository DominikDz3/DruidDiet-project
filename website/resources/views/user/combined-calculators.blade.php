@extends('layouts.app')

@section('title', 'Kalkulatory - ' . config('app.name'))

@push('head_styles')
{{-- Style dla BMI i innych kalkulatorów zostaną przeniesione do nordic.css --}}
@endpush

@section('content')
<section class="user-dashboard py-5 container">
    <div class="row">
        <aside class="col-md-3 mb-4">
            {{-- Menu boczne bez zmian --}}
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
                    <h4 class="mb-0 fw-bold main-card-title">Twoje Kalkulatory Zdrowotne</h4>
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
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        {{-- KALKULATOR WODY --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 {{ $activeCalculator === 'water' ? 'border-primary shadow' : '' }}">
                                <div class="card-header py-3"><h5 class="mb-0 fw-bold card-title">Zapotrzebowanie na wodę</h5></div>
                                <div class="card-body p-3">
                                    <form method="POST" action="{{ route('calculators.water.calculate') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="water_weight_input" class="form-label">Twoja waga (kg):</label>
                                            <input type="number" step="0.1" class="form-control @error('water_weight') is-invalid @enderror" id="water_weight_input" name="water_weight" value="{{ old('water_weight', $waterCalculation['weightInput'] ?? '') }}" required>
                                            @error('water_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="water_activity_level_input" class="form-label">Poziom aktywności:</label>
                                            <select class="form-select @error('water_activity_level') is-invalid @enderror" id="water_activity_level_input" name="water_activity_level" required>
                                                <option value="" disabled {{ old('water_activity_level', $waterCalculation['activityLevelInput'] ?? '') == '' ? 'selected' : '' }}>Wybierz</option>
                                                <option value="1.0" {{ old('water_activity_level', $waterCalculation['activityLevelInput'] ?? '') == '1.0' ? 'selected' : '' }}>Niska</option>
                                                <option value="1.2" {{ old('water_activity_level', $waterCalculation['activityLevelInput'] ?? '') == '1.2' ? 'selected' : '' }}>Umiarkowana</option>
                                                <option value="1.4" {{ old('water_activity_level', $waterCalculation['activityLevelInput'] ?? '') == '1.4' ? 'selected' : '' }}>Wysoka</option>
                                            </select>
                                            @error('water_activity_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <button type="submit" class="btn button btn-sm">Oblicz</button>
                                    </form>
                                    @if(isset($waterCalculation['needed']) && $activeCalculator === 'water')
                                        <hr class="my-3 themed-hr">
                                        <div class="alert alert-success mt-3" role="alert">
                                            Szacowane zapotrzebowanie: <strong>{{ $waterCalculation['needed'] }} L</strong>.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- FORMULARZ KALKULATORA BMI --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 {{ $activeCalculator === 'bmi' ? 'border-primary shadow' : '' }}">
                                <div class="card-header py-3"><h5 class="mb-0 fw-bold card-title">Kalkulator BMI</h5></div>
                                <div class="card-body p-3 bmi-calculator-section">
                                    <form method="POST" action="{{ route('calculators.bmi.calculate') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="bmi_height_input" class="form-label">Wzrost (cm):</label>
                                            <input type="number" step="1" class="form-control @error('bmi_height') is-invalid @enderror" id="bmi_height_input" name="bmi_height" placeholder="np. 175" value="{{ old('bmi_height', $bmiCalculation['heightInput'] ?? '') }}" required>
                                            @error('bmi_height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="bmi_weight_input" class="form-label">Waga (kg):</label>
                                            <input type="number" step="0.1" class="form-control @error('bmi_weight') is-invalid @enderror" id="bmi_weight_input" name="bmi_weight" placeholder="np. 70.5" value="{{ old('bmi_weight', $bmiCalculation['weightInput'] ?? '') }}" required>
                                            @error('bmi_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        @auth
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="save_bmi_result_panel" id="save_bmi_result_panel" value="1" {{ old('save_bmi_result_panel', true) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="save_bmi_result_panel">Zapisz ten wynik BMI</label>
                                            </div>
                                        @endauth
                                        <button type="submit" class="btn button btn-sm">Oblicz BMI</button>
                                    </form>

                                    @if(isset($bmiCalculation['value']) && $bmiCalculation['value'] > 0 && $activeCalculator === 'bmi')
                                        <hr class="my-3 themed-hr">
                                        <div class="bmi-result-display alert {{ $bmiCalculation['alertClass'] ?? 'alert-info' }}">
                                            Twój wynik BMI:
                                            <h4 class="my-1">
                                                <strong>{{ number_format($bmiCalculation['value'], 2) }}</strong>
                                                @if(isset($bmiCalculation['category']))
                                                    <span class="badge bg-{{ str_replace('alert-', '', $bmiCalculation['alertClass']) }}">{{ $bmiCalculation['category'] }}</span>
                                                @endif
                                            </h4>
                                            <small>Obliczono dla: {{ $bmiCalculation['heightInput'] }}cm, {{ $bmiCalculation['weightInput'] }}kg ({{ $bmiCalculation['calculation_date'] ? $bmiCalculation['calculation_date']->format('d.m.Y H:i') : '' }})</small>
                                        </div>
                                    @elseif ($latestBmiFromDb && (!isset($bmiCalculation['value']) || $bmiCalculation['value'] <= 0) ) {{-- Pokaż tylko jeśli nie ma aktywnego wyniku z formularza --}}
                                        <hr class="my-3 themed-hr">
                                        <div class="bmi-result-display">
                                            Ostatni zapisany wynik BMI ({{ $latestBmiFromDb->created_at->format('d.m.Y') }}):
                                            @php
                                                $bmiCategoryLatest = ''; $alertClassLatest = 'alert-info';
                                                if ($latestBmiFromDb->bmi_value < 18.5) { $bmiCategoryLatest = 'Niedowaga'; $alertClassLatest = 'alert-warning'; }
                                                elseif ($latestBmiFromDb->bmi_value < 25) { $bmiCategoryLatest = 'Waga prawidłowa'; $alertClassLatest = 'alert-success'; }
                                                elseif ($latestBmiFromDb->bmi_value < 30) { $bmiCategoryLatest = 'Nadwaga'; $alertClassLatest = 'alert-warning'; }
                                                elseif ($latestBmiFromDb->bmi_value >=30) { $bmiCategoryLatest = 'Otyłość'; $alertClassLatest = 'alert-danger'; }
                                            @endphp
                                            <h4 class="my-1">
                                                <strong>{{ number_format($latestBmiFromDb->bmi_value, 2) }}</strong>
                                                <span class="badge bg-{{ str_replace('alert-', '', $alertClassLatest) }}">{{ $bmiCategoryLatest }}</span>
                                            </h4>
                                        </div>
                                    @endif
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

@push('scripts')
{{-- Możesz dodać tu specyficzne skrypty JS, jeśli będą potrzebne --}}
@endpush