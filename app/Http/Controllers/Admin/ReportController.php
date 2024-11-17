<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date').' 00:00:00') : Carbon::now()->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date').' 23:59:59') : Carbon::now()->endOfDay();

        $orders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatus::COMPLETED)
            ->get();
        $totalRevenue = $orders->sum('total_amount');

        return view('admin.reports.revenue', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
    }

}
