@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Chi Tiết Đơn Hàng #{{ $order->id }}</h1>

    <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</p>
    <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
    <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method->label() }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} đ</p>
    <p><strong>Giảm giá:</strong> {{ number_format($order->discount) }} đ</p>

    <h3>Sản phẩm</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price) }} đ</td>
                <td>{{ number_format($item->quantity * $item->unit_price) }} đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
