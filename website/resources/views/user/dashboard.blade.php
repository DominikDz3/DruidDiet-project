@extends('layouts.user')

@section('content')
    <h2>Panel Użytkownika</h2>
    <p>Witaj, {{ auth()->user()->name }} (user)</p>
@endsection