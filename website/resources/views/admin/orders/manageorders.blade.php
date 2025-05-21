@extends('layouts.admin')

@section('title', 'Zarządzanie Zamówieniami')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Zamówienia</h1>
    <p class="mb-4">Lista wszystkich zamówień w systemie.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formularz wyszukiwania i filtrowania --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Szukaj ID, email, imię..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Wszystkie statusy</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtruj</button>
            </div>
            {{-- Opcjonalny przycisk dodawania
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.orders.create') }}" class="btn btn-success">Dodaj Nowe Zamówienie</a>
            </div>
            --}}
        </div>
    </form>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Lista Zamówień</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
    {{-- ZMIANA: Dodana klasa .table-light dla lekkiego tła nagłówka --}}
    <thead class="table-light">
        <tr>
            {{-- ZMIANA: Polskie nazwy i wyrównanie tekstu --}}
            <th scope="col" class="text-center">ID</th>
            <th scope="col">Klient</th>
            <th scope="col" class="text-center">Data zamówienia</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-end">Suma</th>
            <th scope="col" class="text-center">Akcje</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $order)
        <tr>
            {{-- ZMIANA: Wyrównanie ID do środka --}}
            <td class="text-center">#{{ $order->order_id }}</td>
            <td>
                @if($order->user)
                    {{ $order->user->name }} {{ $order->user->surname }}
                    <small class="d-block">{{ $order->user->email }}</small>
                @else
                    Użytkownik usunięty
                @endif
            </td>
            {{-- ZMIANA: Wyrównanie daty do środka --}}
            <td class="text-center">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d H:i') : 'Brak' }}</td>
            {{-- ZMIANA: Wyrównanie statusu do środka --}}
            <td class="text-center">
                <span class="badge bg-{{ match(strtolower($order->status)) {
                    'oczekujące' => 'warning',
                    'w przygotowaniu' => 'info',
                    'wysłane' => 'primary',
                    'zakończone' => 'success',
                    'anulowane' => 'danger',
                    'zwrócone' => 'dark',
                    default => 'secondary'
                } }} {{ in_array(strtolower($order->status), ['oczekujące', 'w przygotowaniu']) ? 'text-dark' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            {{-- ZMIANA: Wyrównanie sumy do prawej --}}
            <td class="text-end">{{ number_format($order->total_price, 2, ',', ' ') }} zł</td>
            {{-- ZMIANA: Wyrównanie akcji do środka --}}
            <td class="text-center">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info btn-sm" title="Podgląd">
                    <i class="bi bi-eye-fill"></i> Podgląd
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary btn-sm" title="Edytuj">
                <i class="bi bi-pencil-fill"></i> Edytuj
            </a>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć to zamówienie? Tej operacji nie można cofnąć.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" title="Usuń">
                    <i class="bi bi-trash-fill"></i> Usuń
                </button>
            </form>
</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Brak zamówień do wyświetlenia.</td>
        </tr>
        @endforelse
    </tbody>
</table>
            </div>
            <div class="mt-3">
                {{ $orders->appends(request()->query())->links() }} {{-- Paginacja z zachowaniem filtrów --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
@endpush