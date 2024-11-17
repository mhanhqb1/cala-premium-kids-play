<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
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

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/add-to-cart', [PosController::class, 'addToCart'])->name('addToCart');
        Route::post('/remove-from-cart', [PosController::class, 'removeFromCart'])->name('removeFromCart');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
        Route::post('/hold-order', [PosController::class, 'holdOrder'])->name('holdOrder');
        Route::get('/hold-orders', [PosController::class, 'showHoldOrders'])->name('holdOrders');
        Route::get('/resume-order/{order}', [PosController::class, 'resumeOrder'])->name('resumeOrder');
        Route::get('/search-products', [PosController::class, 'searchProducts'])->name('searchProducts');
        Route::post('/create-customer', [PosController::class, 'createCustomer'])->name('createCustomer');
        Route::get('/orders/on-hold', [PosController::class, 'getOnHoldOrders'])->name('orders.onHold');
        Route::post('/orders/delete-on-hold', [PosController::class, 'deleteOnHoldOrder'])->name('orders.deleteOnHold');
        Route::get('/user/{id}', [PosController::class, 'getUserInfo'])->name('user.info');
        Route::post('/cart/update-quantity', [PosController::class, 'updateCartItemQuantity'])->name('cart.updateQuantity');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
    });

    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
