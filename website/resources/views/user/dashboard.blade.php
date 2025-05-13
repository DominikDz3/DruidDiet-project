@extends('layouts.app')

@section('content')
    <section class="user-dashboard py-5 container">
        <div class="row">
            <aside class="col-md-3 mb-4">
                <div class="list-group rounded-3 overflow-hidden">
                    <a href="#" class="list-group-item list-group-item-action active-custom">
                        <i class="bi bi-person-circle me-2"></i> Konto
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-basket me-2"></i> Zamówienia
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i> Ustawienia konta
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Wyloguj się
                        </button>
                    </form>
                </div>
            </aside>

            <div class="col-md-9">
                <div class="p-4 bg-white rounded-3 shadow-sm">
                    <h2 class="fw-bold text-success">{{ Auth::user()->name}} {{Auth::user()->surname}}</h2>
                    <p class="text-muted"></p>
                </div>
            </div>
        </div>
    </section>
@endsection