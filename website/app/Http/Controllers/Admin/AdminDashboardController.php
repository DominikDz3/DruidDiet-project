<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(29);

        $ordersLast30Days = Order::whereBetween('order_date', [$startDate, $endDate])->get();

        $orderCountLast30Days = $ordersLast30Days->count();
        $totalRevenueLast30Days = $ordersLast30Days->sum('total_price');
        $averageOrderValueLast30Days = $orderCountLast30Days > 0 ? $totalRevenueLast30Days / $orderCountLast30Days : 0;

        $days = [];
        $dailyRevenue = [];
        $dailyOrders = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->toDateString();
            $days[] = $date->format('d.m');
            $dailyRevenue[$formattedDate] = 0;
            $dailyOrders[$formattedDate] = 0;
        }

        $revenueData = Order::whereBetween('order_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date');

        foreach ($revenueData as $date => $data) {
            if (isset($dailyRevenue[$date])) {
                $dailyRevenue[$date] = (float) $data->total;
            }
        }

        $orderCountData = Order::whereBetween('order_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date');

        foreach ($orderCountData as $date => $data) {
            if (isset($dailyOrders[$date])) {
                $dailyOrders[$date] = (int) $data->count;
            }
        }
        
        $chartDailyRevenueValues = array_values($dailyRevenue);
        $chartDailyOrdersValues = array_values($dailyOrders);
        $chartLabels = $days;

        return view('admin.dashboard', compact(
            'orderCountLast30Days',
            'totalRevenueLast30Days',
            'averageOrderValueLast30Days',
            'chartLabels',
            'chartDailyRevenueValues',
            'chartDailyOrdersValues'
        ));
    }
}