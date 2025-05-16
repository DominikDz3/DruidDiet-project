{{-- resources/views/errors/404.blade.php --}}

@extends('layouts.app')

@section('title', 'Strona wygasła')

@section('header')
    <header class="bg-light py-3">
        <div class="container">
            <h1 class="text-center">{{ config('app.name', 'DruidDiet') }}</h1>
        </div>
    </header>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="alert alert-danger" role="alert">
                    <h1><i class="bi bi-exclamation-triangle-fill me-2"></i>Błąd 419</h1>
                    <h1>Strona wygasła</h1>
                    <p class="mb-0">Twoja sesja wygasła ze względów bezpieczeństwa.</p>
                    <p class="mt-3"><a href="{{ route('home') }}" class="btn btn-primary">Wróć do strony głównej</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('partials.footer')
@endsection