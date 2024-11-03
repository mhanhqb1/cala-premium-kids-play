@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách sản phẩm</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary float-right">Thêm sản phẩm</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Danh mục</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}" style="width: 100px; height: auto; margin-right: 5px;">
                            @endforeach
                            @if($product->images->isEmpty())
                                <span>Không có hình ảnh</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->formatted_price }}</td>
                        <td>{{ $product->formatted_stock }}</td>
                        <td>
                            @foreach($product->categories as $category)
                                <span class="badge badge-primary">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
