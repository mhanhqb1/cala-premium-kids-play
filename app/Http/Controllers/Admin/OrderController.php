<?php
namespace App\Http\Controllers\Admin;

use App\Constants\ConstantCommon;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $limit = ConstantCommon::PAGE_LIMIT;
        $orders = Order::with('user')->latest()->paginate($limit);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }
}
