@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>POS - Bán Hàng</h1>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Form tìm kiếm và lọc sản phẩm -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <input type="text" id="searchProduct" class="form-control" placeholder="Tìm kiếm sản phẩm">
                </div>
                <div class="col-md-4">
                    <select id="customerSelect" class="form-control select2">
                        <option value="{{ $guest->id }}">Khách vãng lai</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" id="searchButton">Tìm kiếm</button>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <button class="btn btn-secondary category-button" data-category-id="">Tất cả</button>
                    @foreach($categories as $category)
                        <button class="btn btn-secondary category-button" data-category-id="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div id="productList" class="row">
                @include('admin.pos.partials.product_list', ['products' => $products])
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
    let categoryId = '';
    let customerId = '{{ $guest->id }}';
    $('#searchButton').on('click', function() {
        fetchFilteredProducts();
    });

    $('#customerSelect').on('change', function() {
        customerId = $(this).val();
    });

    $('#searchProduct').on('change', function() {
        fetchFilteredProducts();
    });

    $('.category-button').on('click', function() {
        // Xóa active từ các nút khác và thêm vào nút được chọn
        $('.category-button').removeClass('btn-primary').addClass('btn-secondary');
        $(this).removeClass('btn-secondary').addClass('btn-primary');

        // Lấy ID của danh mục từ nút được chọn
        categoryId = $(this).data('category-id');
        fetchFilteredProducts();
    });

    function fetchFilteredProducts() {
        let search = $('#searchProduct').val();

        $.ajax({
            url: "{{ route('admin.pos.searchProducts') }}",
            type: "GET",
            data: {
                search: search,
                category: categoryId
            },
            success: function(data) {
                $('#productList').html(data.html); // Cập nhật danh sách sản phẩm
            },
            error: function() {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    }

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
        $.post("{{ route('admin.pos.checkout') }}?user_id="+customerId, { _token: "{{ csrf_token() }}" }, function(data) {
            if (data.success) {
                alert('Thanh toán thành công!');
                $('#cart').html(''); // Xóa giỏ hàng sau khi thanh toán
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });

    $('#hold-order').on('click', function() {
        $.post("{{ route('admin.pos.holdOrder') }}?user_id="+customerId, { _token: "{{ csrf_token() }}" }, function(data) {
            if (data.success) {
                alert(data.message);
                $('#cart').html(''); // Xóa giỏ hàng sau khi tạm giữ
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });
});
</script>
@endPush
