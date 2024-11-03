@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa sản phẩm</h3>
    </div>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>
            <div class="form-group">
                <label for="stock">Tồn kho</label>
                <input type="number" id="stock" name="stock" class="form-control" value="{{ $product->stock }}" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection
