
<table class="table">
    <thead>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @if(session('cart'))
        @foreach(session('cart') as $id => $details)
        <tr>
            <td>{{ $details['name'] }}</td>
            <td>
                <input type="number" class="form-control item-quantity" data-id="{{ $id }}" value="{{ $details['quantity'] }}" min="1"/>
            </td>
            <td>{{ number_format($details['price']) }} đ</td>
            <td>{{ number_format($details['quantity'] * $details['price']) }} đ</td>
            <td><span class="btn btn-danger remove-from-cart" data-id="{{ $id }}">Xóa</span></td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">Giỏ hàng trống</td>
        </tr>
    @endif
    </tbody>
</table>
