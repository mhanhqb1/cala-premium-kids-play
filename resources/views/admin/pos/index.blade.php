@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>POS - Bán Hàng</h1>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row">
        <!-- Danh sách sản phẩm -->
        <div class="col-md-8">
            <h2>Sản phẩm</h2>
            <div class="product-list d-flex flex-wrap">
                @foreach($products as $product)
                    <div class="product-item card m-2 p-2" style="width: 150px;">
                        <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 100px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->formatted_price }}</p>
                            <button class="btn btn-primary btn-sm add-to-cart" data-id="{{ $product->id }}">Thêm vào giỏ</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Giỏ hàng -->
        <div class="col-md-4">
            <h2>Giỏ hàng</h2>
            <div id="cart">
                <!-- Hiển thị giỏ hàng với các sản phẩm đã chọn -->
            </div>
            <div class="mt-3">
                <button class="btn btn-warning" id="hold-order">Tạm giữ đơn hàng</button>
                <button class="btn btn-success" id="checkout">Thanh toán</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Thêm sản phẩm vào giỏ hàng
    $('.add-to-cart').on('click', function() {
        let productId = $(this).data('id');
        $.post("{{ route('admin.pos.addToCart') }}", { id: productId, _token: "{{ csrf_token() }}" }, function(data) {
            $('#cart').html(data.cartHtml);
        });
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $('#cart').on('click', '.remove-from-cart', function() {
        let productId = $(this).data('id');
        $.post("{{ route('admin.pos.removeFromCart') }}", { id: productId, _token: "{{ csrf_token() }}" }, function(data) {
            $('#cart').html(data.cartHtml);
        });
    });

    // Thanh toán
    $('#checkout').on('click', function() {
        $.post("{{ route('admin.pos.checkout') }}", { _token: "{{ csrf_token() }}" }, function(data) {
            if (data.success) {
                alert('Thanh toán thành công!');
                $('#cart').html(''); // Xóa giỏ hàng sau khi thanh toán
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });

    $('#hold-order').on('click', function() {
        $.post("{{ route('admin.pos.holdOrder') }}", { _token: "{{ csrf_token() }}" }, function(data) {
            if (data.success) {
                alert(data.message);
                $('#cart').html(''); // Xóa giỏ hàng sau khi tạm giữ
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });
});
</script>
@endPush
