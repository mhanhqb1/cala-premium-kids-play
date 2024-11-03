@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa sản phẩm</h3>
    </div>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
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
                <label>Hình ảnh hiện tại</label>
                <div class="current-images">
                    @foreach($product->images as $image)
                        <div class="image-container" style="display: inline-block; margin-right: 10px; position: relative;">
                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                            <span class="btn btn-danger btn-sm delete-image-form" data-action="{{ route('admin.products.images.destroy', $image->id) }}" style="background: none; border: none; color: red; cursor: pointer;">&times;</span>

                            <div>
                                <label>
                                    <input type="radio" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}>
                                    Chọn làm hình chính
                                </label>
                            </div>
                        </div>
                    @endforeach
                    @if($product->images->isEmpty())
                        <span>Không có hình ảnh</span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="images">Thêm hình ảnh mới</label>
                <input type="file" name="images[]" class="form-control" id="images" accept="image/*" multiple>
            </div>
            <div class="form-group">
                <div id="preview-images" class="preview-images" style="display: flex; flex-wrap: wrap;">
                    <!-- Hình ảnh sẽ hiển thị ở đây -->
                </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-image-form').on('click', function(e) {
        e.preventDefault(); // Ngăn chặn submit form mặc định
        const $this = $(this);
        const actionUrl = $this.attr('data-action');

        if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Xóa hình ảnh từ DOM
                    $this.closest('.image-container').remove();
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra!'); // Thông báo lỗi
                }
            });
        }
    });

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
