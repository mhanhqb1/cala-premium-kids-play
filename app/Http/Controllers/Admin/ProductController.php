<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstantCommon;
use App\Constants\ConstantProduct;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Danh sách sản phẩm
    public function index(Request $request)
    {
        $pageLimit = ConstantCommon::PAGE_LIMIT;
        $query = Product::query();

        // Lọc theo tên sản phẩm
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id !== '') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Lấy danh sách sản phẩm
        $products = $query->with(['categories', 'images'])->paginate($pageLimit);
        $categories = Category::all();
        return view('admin.products.index', compact(
            'products',
            'categories'
        ));
    }

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'category_ids' => 'array', // Danh sách ID của categories
            'category_ids.*' => 'exists:categories,id',
            'images.*' => 'nullable|image|mimes:'.ConstantProduct::IMAGE_TYPES.'|max:'.ConstantProduct::IMAGE_LENGTH,
        ]);

        $product = Product::create($request->only(['name', 'price', 'description', 'stock']));

        // Gán danh mục cho sản phẩm
        $product->categories()->sync($request->category_ids);

        // Xử lý upload hình ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store(ConstantProduct::IMAGE_PATH, 'public');
                $product->images()->create(['image_url' => $imagePath]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'category_ids' => 'array', // Danh sách ID của categories
            'category_ids.*' => 'exists:categories,id',
            'images.*' => 'nullable|image|mimes:'.ConstantProduct::IMAGE_TYPES.'|max:'.ConstantProduct::IMAGE_LENGTH,
        ]);

        $product->update($request->only(['name', 'price', 'description', 'stock']));

        // Cập nhật danh mục cho sản phẩm
        $product->categories()->sync($request->category_ids);

        // Xử lý upload hình ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store(ConstantProduct::IMAGE_PATH, 'public');
                $product->images()->create(['image_url' => $imagePath]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    // Xóa sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa!');
    }
}
