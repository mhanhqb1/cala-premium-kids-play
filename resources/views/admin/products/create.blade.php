@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Thêm sản phẩm</h3>
    </div>
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả sản phẩm"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá</label>
                <input type="number" id="price" name="price" class="form-control" placeholder="Nhập giá sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="stock">Tồn kho</label>
                <input type="number" id="stock" name="stock" class="form-control" placeholder="Nhập số lượng tồn kho" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection
