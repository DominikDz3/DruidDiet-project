@extends('layouts.app')

@section('title', htmlspecialchars($catering->title ?? 'Szczegóły Kateringu'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/css/nordic.css') }}">
@endpush

@section('content')
<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="catering-detail-container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="catering-image-wrapper">
                            @if ($catering->photo)
                                <img src="{{ asset('storage/' . $catering->photo) }}" class="img-fluid" alt="{{ htmlspecialchars($catering->title ?? 'Katering') }}">
                            @else
                                <img src="https://via.placeholder.com/800x400.png?text={{ urlencode(htmlspecialchars($catering->title ?? 'Katering')) }}" class="img-fluid" alt="{{ htmlspecialchars($catering->title ?? 'Katering') }}">
                            @endif
                        </div>

                        <div class="catering-main-info mt-4">
                            <h2 class="card-title-detail">{{ ($catering->title ?? 'Brak tytułu') }}</h2>
                            <p class="text-muted"><small><strong>Typ:</strong> {{ htmlspecialchars($catering->type ?? 'N/A') }}</small></p>

                            <h5 class="mt-4" style="color: #4a6b5a;">Opis:</h5>
                            <p class="card-text fs-5">{{ nl2br(htmlspecialchars($catering->description ?? 'Brak opisu.')) }}</p>

                            @if (!empty($catering->elements))
                                <h5 class="mt-4" style="color: #4a6b5a;">Skład:</h5>
                                <p class="card-text">{{ ($catering->elements) }}</p>
                            @endif

                            @if (!empty($catering->allergens))
                                <h5 class="mt-4" style="color: #d9534f;">Alergeny:</h5>
                                <p class="card-text text-danger fw-bold">{{ htmlspecialchars($catering->allergens) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="catering-sidebar">
                            <h3 class="mb-4" style="color: #4a6b5a;">Informacje o katingu</h3>
                            <p class="price-tag-detail">{{ htmlspecialchars(number_format($catering->price ?? 0, 2, ',', ' ')) }} zł</p>

                            {{-- FORMULARZ DODAWANIA DO KOSZYKA --}}
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $catering->catering_id }}">
                                <input type="hidden" name="product_type" value="catering">
                                <div class="d-flex align-items-center mb-3">
                                    <label for="quantity" class="form-label mb-0 me-2">Ilość:</label>
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-quantity" aria-label="Ilość" id="quantity">
                                </div>
                                <button type="submit" class="btn button">
                                    <i class="bi bi-cart-plus"></i> Dodaj do koszyka
                                </button>
                            </form>

                            <hr>

                            <div class="mt-3">
                                <p class="mb-1"><small>Dostępność: <span class="text-success fw-bold">Dostępny</span></small></p>
                                <p><small>Dostawa: <span class="fw-bold">24-48h</span></small></p>
                            </div>
                        </div>

                        <div class="mt-4 text-center text-md-start">
                            <a href="{{ route('caterings.index') }}" class="back-link">
                                <i class="bi bi-arrow-left"></i> Powrót do listy kateringów
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection