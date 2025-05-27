@extends('layouts.admin') {{-- Upewnij się, że używasz odpowiedniego layoutu dla admina --}}

@section('title', 'Zarządzanie Kateringami - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kateringi</h1>
        <a href="{{ route('admin.caterings.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Dodaj Nowy Katering
        </a>
    </div>

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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Lista Kateringów</h6>
            {{-- Formularz wyszukiwania dla cateringów --}}
            <form action="{{ route('admin.caterings.index') }}" method="GET" class="mt-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Szukaj po tytule, typie..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit" style="border-color: #4a6b5a; color: #4a6b5a;">Szukaj</button>
                    @if(request('search'))
                        <a href="{{ route('admin.caterings.index') }}" class="btn btn-outline-danger" style="border-color: #dc3545; color: #dc3545;">Wyczyść</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light" style="border-color: #4a6b5a;">
                        <tr>
                            <th>ID</th>
                            <th>Tytuł</th>
                            <th>Typ</th>
                            <th>Cena</th>
                            <th>Zdjęcie</th>
                            <th>Opis (Fragment)</th>
                            <th>Alergeny (Fragment)</th>
                            <th style="width: 150px;">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($caterings as $catering)
                        <tr>
                            <td>{{ $catering->catering_id }}</td>
                            <td>{{ $catering->title }}</td>
                            <td>{{ $catering->type }}</td>
                            <td>{{ number_format($catering->price, 2) }} zł</td>
                            <td>
                                @if ($catering->photo && file_exists(public_path($catering->photo)))
                                    <img src="{{ asset($catering->photo) }}" alt="{{ $catering->title }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <img src="https://via.placeholder.com/80x80.png?text=Brak" alt="Brak zdjęcia" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                @endif
                            </td>
                            <td>{{ Str::limit($catering->description, 50) }}</td> {{-- Ogranicz opis --}}
                            <td>{{ $catering->allergens ? Str::limit($catering->allergens, 30) : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.caterings.edit', $catering->catering_id) }}" class="btn btn-sm btn-warning me-1" title="Edytuj">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.caterings.destroy', $catering->catering_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten katering? Tej operacji nie można cofnąć.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Usuń">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Nie znaleziono kateringów.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $caterings->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection