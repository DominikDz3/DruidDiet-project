@extends('layouts.app') {{-- Użyj głównego layoutu aplikacji, np. app.blade.php --}}

@section('title', 'Mój Koszyk - DruidDiet')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mój Koszyk</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($cart))
        <div class="alert alert-info">Twój koszyk jest pusty.</div>
        <a href="{{ route('home') }}" class="btn btn-primary">Kontynuuj zakupy</a> {{-- Lub link do strony z produktami --}}
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="width: 10%;">Zdjęcie</th>
                        <th scope="col" style="width: 30%;">Produkt</th>
                        <th scope="col" class="text-center" style="width: 15%;">Cena/szt.</th>
                        <th scope="col" class="text-center" style="width: 15%;">Ilość</th>
                        <th scope="col" class="text-end" style="width: 15%;">Suma</th>
                        <th scope="col" class="text-center" style="width: 15%;">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $cartItemId => $item)
                    <tr>
                        <td>
                            {{-- Pamiętaj o obsłudze wyświetlania zdjęcia --}}
                            {{-- Jeśli $item['photo'] to ścieżka: --}}
                            {{-- <img src="{{ asset($item['photo']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="max-height: 70px; object-fit: cover;"> --}}
                            {{-- Jeśli to dane binarne, potrzebna inna logika lub placeholder --}}
                            <img src="https://via.placeholder.com/70" alt="{{ $item['name'] }}" class="img-fluid rounded">
                        </td>
                        <td>
                            <h5 class="mb-0">{{ $item['name'] }}</h5>
                            <small class="text-muted">Typ: {{ ucfirst($item['type']) }}</small>
                        </td>
                        <td class="text-center">{{ number_format($item['price'], 2, ',', ' ') }} zł</td>
                        <td class="text-center">
                            <form action="{{ route('cart.update', $cartItemId) }}" method="POST" class="d-inline-flex align-items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-outline-secondary btn-sm ms-2" title="Aktualizuj ilość">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} zł</td>
                        <td class="text-center">
                            <form action="{{ route('cart.remove', $cartItemId) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Usuń z koszyka">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Podsumowanie Koszyka</h5>
                        <p class="card-text d-flex justify-content-between">
                            <span>Łącznie:</span>
                            <strong>{{ number_format($totalPrice, 2, ',', ' ') }} zł</strong>
                        </p>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-success">Przejdź do kasy (Wkrótce)</a>
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Wyczyść Koszyk</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
             <a href="{{ route('home') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kontynuuj zakupy</a>
        </div>
    @endif
</div>
@endsection