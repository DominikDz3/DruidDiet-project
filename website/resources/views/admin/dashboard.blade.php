@extends('layouts.admin')

@section('content')
    <h2>Panel Administratora</h2>
    <p>Witaj, {{ auth()->user()->name }} (admin)</p>
@endsection