@extends('layouts.admin')

@section('title', 'Dashboard - Panel Administratora')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left-color: #4a6b5a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4a6b5a;">Przychody (Ost. 30 dni)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenueLast30Days, 2, ',', ' ') }} zł</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left-color: #5cb85c !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Zamówienia (Ost. 30 dni)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderCountLast30Days }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left-color: #5bc0de !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Śr. Wartość Zam. (Ost. 30 dni)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($averageOrderValueLast30Days, 2, ',', ' ') }} zł</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Dzienne Przychody (Ost. 30 dni)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Dzienna Liczba Zamówień (Ost. 30 dni)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dailyOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    window.chartData = {
        labels: @json($chartLabels),
        dailyRevenue: @json($chartDailyRevenueValues),
        dailyOrders: @json($chartDailyOrdersValues)
    };
</script>

<script src="{{ asset('js/charts.js') }}" defer></script>
@endpush