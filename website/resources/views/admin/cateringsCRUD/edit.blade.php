@extends('layouts.admin')

@section('title', 'Edytuj Katering - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edytuj Katering: {{ $catering->title }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Formularz edycji cateringu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.caterings.update', $catering->catering_id) }}" method="POST">
                @method('PUT') {{-- Ważne: Laravel potrzebuje PUT dla metody update --}}
                {{-- Ważne: @csrf zostało przeniesione do partiala --}}
                @include('partials.admin_catering_form', ['catering' => $catering]) {{-- Przekazujemy istniejący model cateringu --}}
            </form>
        </div>
    </div>
</div>
@endsection