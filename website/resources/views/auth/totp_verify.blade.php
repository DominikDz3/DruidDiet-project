@extends('layouts.app')
@section('title', 'Weryfikacja Dwuskładnikowa - ' . config('app.name'))

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg rounded-3 border-0">
                <div class="card-header text-center py-4" style="background-color: #4a6b5a; color: white; border-bottom: none;">
                    <h4 class="mb-0 fw-bold">Weryfikacja Dwuskładnikowa</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <p class="text-center text-muted mb-4">Wprowadź 6-cyfrowy kod z aplikacji uwierzytelniającej, aby dokończyć logowanie.</p>

                    @if ($errors->has('totp_code'))
                        <div class="alert alert-danger text-center p-2 mb-3">
                            {{ $errors->first('totp_code') }}
                        </div>
                    @endif
                     @if (session('error')) {{-- Ogólny błąd, jeśli potrzebny --}}
                        <div class="alert alert-danger text-center p-2 mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.totp.verify') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="totp_code" class="form-label visually-hidden">Kod TOTP</label>
                            <input type="text"
                                   class="form-control form-control-lg text-center @error('totp_code') is-invalid @enderror"
                                   id="totp_code"
                                   name="totp_code"
                                   required
                                   autofocus
                                   pattern="\d{6}"
                                   maxlength="6"
                                   inputmode="numeric"
                                   autocomplete="one-time-code"
                                   placeholder="------"
                                   style="letter-spacing: 0.5em;">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" style="background-color: #4a6b5a; border-color: #4a6b5a;">
                                <i class="bi bi-check-circle-fill me-2"></i> Weryfikuj Kod
                            </button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-muted small">Anuluj i wróć do logowania</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection