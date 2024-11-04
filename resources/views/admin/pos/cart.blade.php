<div>
    @if(session('cart'))
        <ul>
            @foreach(session('cart') as $id => $details)
                <li>{{ $details['name'] }} - {{ $details['quantity'] }} x {{ number_format($details['price']) }} VND
                    <button class="remove-from-cart" data-id="{{ $id }}">Xóa</button>
                </li>
            @endforeach
        </ul>
        <p>Tổng cộng: {{ number_format(array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], session('cart')))) }} VND</p>
    @else
        <p>Giỏ hàng trống</p>
    @endif
</div>
