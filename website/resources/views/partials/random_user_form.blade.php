@extends('layouts.admin')

@section('title', 'Losuj Kupon dla Użytkownika - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Losuj Kupon dla Użytkownika</h1>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Powrót do listy kuponów
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Określ wartość rabatu dla nowego kuponu</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {!! session('success') !!}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.coupons.generateForRandomUser') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="discount_value_percentage" class="form-label">Wartość rabatu (%): <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('discount_value_percentage') is-invalid @enderror" 
                                   id="discount_value_percentage" 
                                   name="discount_value_percentage" 
                                   value="{{ old('discount_value_percentage', 10) }}" 
                                   min="1" 
                                   max="100" 
                                   required>
                            @error('discount_value_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Podaj wartość procentową rabatu (np. 10 dla 10%).</div>
                        </div>
                    </div>
                </div>
                
                <hr>
                <p>Po kliknięciu przycisku system wylosuje jednego użytkownika (z rolą "user", jeśli tacy istnieją, w przeciwnym wypadku dowolnego) i przypisze mu nowo wygenerowany, unikalny kod rabatowy o podanej wartości.</p>

                <button type="submit" class="btn btn-lg" style="background-color: #4a6b5a; color: white; border-color: #4a6b5a;">
                    <i class="fas fa-random me-2"></i> Losuj i Przypisz Kupon
                </button>
            </form>
        </div>
    </div>
</div>
@endsection