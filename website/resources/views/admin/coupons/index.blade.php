@extends('layouts.admin')

@section('title', 'Zarządzanie Kodami Rabatowymi - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kody Rabatowe</h1>
        <div>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm shadow-sm me-2" style="background-color: #4a6b5a; border-color: #4a6b5a;">
                <i class="fas fa-plus fa-sm text-white-50"></i> Wygeneruj Nowy Kod
            </a>
            <a href="{{ route('admin.coupons.randomUserForm') }}" class="btn btn-info btn-sm shadow-sm">
                <i class="fas fa-random fa-sm text-white-50"></i> Losuj Kupon dla Użytkownika
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Lista Kodów Rabatowych</h6>
            </div>
            <form action="{{ route('admin.coupons.index') }}" method="GET" class="mt-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Szukaj po kodzie, nazwisku, emailu..." value="{{ $search ?? '' }}">
                    <button class="btn btn-outline-secondary" type="submit" style="border-color: #4a6b5a; color: #4a6b5a;">
                        <i class="fas fa-search"></i> Szukaj
                    </button>
                    @if($search)
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-danger" style="border-color: #dc3545; color: #dc3545;" title="Wyczyść filtr">
                            <i class="fas fa-times"></i> Wyczyść
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kod</th>
                            <th>Rabat</th>
                            <th>Przypisany do</th>
                            <th>Użyty?</th>
                            <th>Data utworzenia</th>
                            <th style="width: 120px;">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->coupon_id }}</td>
                            <td><strong>{{ $coupon->code }}</strong></td>
                            <td>{{ $coupon->discount_value * 100 }}%</td>
                            <td>
                                @if($coupon->user)
                                    {{ $coupon->user->name }} {{ $coupon->user->surname }}
                                @else
                                    <span class="text-muted">- Ogólny -</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $coupon->is_used ? 'secondary' : 'success' }}">
                                    {{ $coupon->is_used ? 'Tak' : 'Nie' }}
                                </span>
                            </td>
                            <td>{{ Carbon\Carbon::parse($coupon->created_at)->format('d.m.Y') }}</td>
                            <td>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-warning me-1" title="Edytuj">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten kod rabatowy?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Usuń">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>
                                Nie znaleziono kodów rabatowych.
                                @if($search)
                                    Spróbuj zmodyfikować kryteria wyszukiwania lub <a href="{{ route('admin.coupons.index') }}">wyczyść filtr</a>.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($coupons->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $coupons->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection