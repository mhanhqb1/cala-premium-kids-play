<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // Route xóa hình ảnh
    Route::delete('products/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add-to-cart', [PosController::class, 'addToCart'])->name('pos.addToCart');
    Route::post('/pos/remove-from-cart', [PosController::class, 'removeFromCart'])->name('pos.removeFromCart');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/hold-order', [PosController::class, 'holdOrder'])->name('pos.holdOrder');
    Route::get('/pos/hold-orders', [PosController::class, 'showHoldOrders'])->name('pos.holdOrders');
    Route::get('/pos/resume-order/{order}', [PosController::class, 'resumeOrder'])->name('pos.resumeOrder');
});

require __DIR__.'/auth.php';
