@extends('layouts.admin')

@section('title', 'Edytuj Kod Rabatowy - Panel Administratora')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edytuj Kod Rabatowy: {{ $coupon->code }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #4a6b5a;">Formularz edycji kodu rabatowego</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                @method('PUT')
                @include('partials.admin_coupon_form', ['coupon' => $coupon])
            </form>
        </div>
    </div>
</div>
@endsection
