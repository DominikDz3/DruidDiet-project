@extends('layouts.admin')

@section('title', 'Generuj Nowy Kod Rabatowy - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Generuj Nowy Kod Rabatowy</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Formularz nowego kodu rabatowego</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @include('partials.admin_coupon_form')
            </form>
        </div>
    </div>
</div>
@endsection
