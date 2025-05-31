@extends('layouts.app')

@section('title', 'Mój Koszyk - DruidDiet')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mój Koszyk</h1>

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
    {{-- Dodano alerty dla kuponów --}}
    @if(session('coupon_error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('coupon_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     @if(session('coupon_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('coupon_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(empty($cart) && !session()->has('applied_coupon'))
        <div class="alert alert-info">Twój koszyk jest pusty.</div>
        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Kontynuuj zakupy</a>
        </div>
    @else
        <div class="table-responsive mb-4">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 10%;">Zdjęcie</th>
                        <th scope="col" style="width: 30%;">Produkt</th>
                        <th scope="col" class="text-center" style="width: 15%;">Cena/szt.</th>
                        <th scope="col" class="text-center" style="width: 20%;">Ilość</th>
                        <th scope="col" class="text-end" style="width: 15%;">Suma</th>
                        <th scope="col" class="text-center" style="width: 10%;">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @php $originalTotalPriceFromView = 0; @endphp {{-- Zmienna do obliczenia sumy w widoku --}}
                    @if(!empty($cart))
                        @foreach($cart as $cartItemId => $item)
                        @php $originalTotalPriceFromView += $item['price'] * $item['quantity']; @endphp
                        <tr>
                            <td>
                                <img src="{{ (!empty($item['photo']) && is_string($item['photo'])) ? asset($item['photo']) : 'https://via.placeholder.com/80x60.png/f8f9fa/6c757d?text='.urlencode($item['name']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="width: 80px; height: 60px; object-fit: cover;">
                            </td>
                            <td>
                                <h5 class="mb-0 fs-6">{{ $item['name'] }}</h5>
                                <small class="text-muted">Typ: {{ ucfirst($item['type']) }}</small>
                            </td>
                            <td class="text-center">{{ number_format($item['price'], 2, ',', ' ') }} zł</td>
                            <td class="text-center">
                                <form action="{{ route('cart.update', $cartItemId) }}" method="POST" class="d-inline-flex align-items-center justify-content-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm" style="width: 60px; text-align: center;" aria-label="Ilość">
                                    <button type="submit" class="btn btn-outline-primary btn-sm ms-2" title="Aktualizuj ilość">
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
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="6" class="text-center">Twój koszyk jest pusty.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row justify-content-between align-items-start">
            <div class="col-md-6 mb-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kontynuuj zakupy</a>

                {{-- FORMULARZ KODU RABATOWEGO --}}
                @if(!session()->has('applied_coupon'))
                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mt-3">
                    @csrf
                    <label for="coupon_code" class="form-label">Masz kod rabatowy?</label>
                    <div class="input-group">
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Wpisz kod" aria-label="Kod rabatowy">
                        <button class="btn btn-outline-secondary" type="submit">Zastosuj</button>
                    </div>
                </form>
                @endif
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Podsumowanie Koszyka</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Suma częściowa:</span>
                            {{-- Używamy $originalTotalPrice przekazanej z kontrolera --}}
                            <span>{{ number_format($originalTotalPrice, 2, ',', ' ') }} zł</span>
                        </div>

                        @if(session()->has('applied_coupon'))
                            @php
                                $couponData = session('applied_coupon');
                                // $discountAmount i $finalTotalPrice są przekazywane z CartController@index
                            @endphp
                            <div class="d-flex justify-content-between mb-1 text-success">
                                <span>Rabat ({{ $couponData['code'] }}):</span>
                                <span>-{{ number_format($discountAmount, 2, ',', ' ') }} zł</span>
                            </div>
                             <form action="{{ route('cart.coupon.remove') }}" method="POST" class="d-inline mb-2">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-danger p-0">Usuń kod rabatowy</button>
                            </form>
                        @endif

                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Łącznie do zapłaty:</span>
                            {{-- Używamy $finalTotalPrice przekazanej z kontrolera --}}
                            <span>{{ number_format($finalTotalPrice, 2, ',', ' ') }} zł</span>
                        </div>

                        @auth
                            @php
                                $userPoints = Auth::user()->loyalty_points ?? 0;
                                $pointsNeededForOrder = (int)round($finalTotalPrice);
                                $canPayWithPoints = $userPoints >= $pointsNeededForOrder && $finalTotalPrice > 0;
                            @endphp

                            <form action="{{ route('checkout.store') }}" method="POST" class="d-grid gap-2">
                                @csrf

                                @if($canPayWithPoints)
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="pay_with_points_switch" name="pay_with_points" value="1">
                                    <label class="form-check-label" for="pay_with_points_switch">
                                        Zapłać punktami lojalnościowymi ({{ $pointsNeededForOrder }} pkt)
                                    </label>
                                </div>
                                <p class="mb-1"><small>Twoje saldo punktów: <strong class="text-success">{{ $userPoints }} pkt</strong></small></p>
                                @else
                                <p class="mb-1"><small>Twoje saldo punktów: <strong class="text-success">{{ $userPoints }} pkt</strong></small></p>
                                    @if($finalTotalPrice > 0) {{-- Pokaż tylko jeśli jest coś do zapłacenia --}}
                                    <small class="text-muted d-block mb-2">Potrzebujesz {{ $pointsNeededForOrder }} pkt, aby opłacić zamówienie punktami (brakuje: {{ $pointsNeededForOrder - $userPoints }} pkt).</small>
                                    @endif
                                @endif

                                {{-- Przycisk składania zamówienia aktywny, jeśli jest co zamawiać (nawet po zniżce 100%) --}}
                                <button type="submit" class="btn btn-success btn-lg" @if(empty($cart)) disabled @endif>
                                    <i class="bi bi-shield-check-fill"></i> Złóż zamówienie
                                </button>
                            </form>
                        @else
                        <div class="alert alert-warning text-center p-2">
                            <small>
                                <a href="{{ route('login', ['redirect' => route('cart.index')]) }}" class="alert-link">Zaloguj się</a> lub
                                <a href="{{ route('register') }}" class="alert-link">zarejestruj</a>, aby złożyć zamówienie.
                            </small>
                        </div>
                        @endauth

                        @if(!empty($cart))
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-grid mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-x-circle"></i> Wyczyść Koszyk
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
