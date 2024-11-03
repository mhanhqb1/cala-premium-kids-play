<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);

        // Xóa hình ảnh khỏi storage
        Storage::disk('public')->delete($image->image_url);

        // Xóa hình ảnh khỏi cơ sở dữ liệu
        $image->delete();

        return redirect()->back()->with('success', 'Xóa hình ảnh thành công');
    }
}
