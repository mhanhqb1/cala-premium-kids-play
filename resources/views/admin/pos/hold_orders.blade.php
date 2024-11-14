@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Đơn Hàng Tạm Giữ</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ngày tạo</th>
                <th>Tổng tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($holdOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ number_format($order->total_price) }} VND</td>
                    <td>
                        <a href="{{ route('admin.pos.resumeOrder', $order->id) }}" class="btn btn-primary btn-sm">Tiếp tục xử lý</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
