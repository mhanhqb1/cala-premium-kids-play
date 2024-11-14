@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu')

@section('content')
<div class="container">
    <form method="GET" action="{{ route('admin.reports.revenue') }}" class="mb-4">
        <div class="form-group">
            <label for="start_date">Ngày bắt đầu:</label>
            <input type="text" name="start_date" id="start_date" class="form-control datepicker"
                   value="{{ request('start_date') ?? $startDate->format('Y-m-d') }}" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="end_date">Ngày kết thúc:</label>
            <input type="text" name="end_date" id="end_date" class="form-control datepicker"
                   value="{{ request('end_date') ?? $endDate->format('Y-m-d') }}" autocomplete="off">
        </div>

        <button type="submit" class="btn btn-primary">Xem báo cáo</button>
    </form>

    <h4>Doanh thu: {{ number_format($totalRevenue) }} đ</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Ngày tạo</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ number_format($order->total_amount) }} đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endSection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize the datepickers
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
@endPush
