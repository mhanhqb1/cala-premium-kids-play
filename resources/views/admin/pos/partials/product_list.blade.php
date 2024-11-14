@foreach($products as $product)
    <div class="col-md-3 mb-3">
        <div class="card product-item">
            <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 100px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">{{ number_format($product->price) }} VND</p>
                <button class="btn btn-primary btn-sm add-to-cart" data-id="{{ $product->id }}" data-price="{{ $product->price }}">Thêm vào giỏ</button>
            </div>
        </div>
    </div>
@endforeach
