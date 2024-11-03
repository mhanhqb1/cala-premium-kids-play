@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Thêm sản phẩm</h3>
    </div>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
            </div>
            <div class="form-group">
                <label for="category_ids">Danh mục sản phẩm</label>
                <select name="category_ids[]" id="category_ids" class="form-control select2" multiple>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ isset($product) && $product->categories->contains($category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="images">Hình ảnh sản phẩm</label>
                <input type="file" name="images[]" class="form-control" id="images" accept="image/*" multiple>
            </div>
            <div class="form-group">
                <div id="preview-images" class="preview-images" style="display: flex; flex-wrap: wrap;">
                    <!-- Hình ảnh sẽ hiển thị ở đây -->
                </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#images').on('change', function(event) {
        const previewContainer = $('#preview-images');
        previewContainer.empty(); // Xóa hình ảnh cũ

        const files = event.target.files;

        $.each(files, function(index, file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = $('<img>', {
                    src: e.target.result,
                    css: {
                        width: '100px', // Đặt kích thước cho hình ảnh
                        height: 'auto',
                        marginRight: '10px'
                    }
                });
                previewContainer.append(img);
            };

            reader.readAsDataURL(file);
        });
    });
});
</script>
@endPush
