@extends('layouts.admin')

@section('title', 'Edytuj Użytkownika - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edytuj Użytkownika: {{ $user->name }} {{ $user->surname }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Formularz edycji użytkownika</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @method('PUT')
                @include('partials.admin_user_form', ['user' => $user, 'roles' => $roles])
            </form>
        </div>
    </div>
</div>
@endsection
