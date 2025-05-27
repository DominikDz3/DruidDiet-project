@extends('layouts.admin')

@section('title', 'Dodaj Nowy Katering - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dodaj Nowy Katering</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Formularz nowego cateringu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.caterings.store') }}" method="POST">
                {{-- Ważne: @csrf i @method('PUT') zostały przeniesione do partiala --}}
                @include('partials.admin_catering_form', ['catering' => new \App\Models\Catering()]) {{-- Przekazujemy pusty model dla formularza tworzenia --}}
            </form>
        </div>
    </div>
</div>
@endsection