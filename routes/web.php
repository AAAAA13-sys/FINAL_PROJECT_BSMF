<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/search-suggestions', [ProductController::class, 'suggestions'])->name('api.search.suggestions');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/confirmation/{id}', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');



    // Reviews
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');


    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');


    // Coupons
    Route::post('/coupon/apply', [\App\Http\Controllers\OrderController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon', [\App\Http\Controllers\OrderController::class, 'removeCoupon'])->name('coupon.remove');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Products
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::post('/products', [AdminController::class, 'productStore'])->name('products.store');
        Route::get('/products/{id}/edit', [AdminController::class, 'productEdit'])->name('products.edit');
        Route::put('/products/{id}', [AdminController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'productDestroy'])->name('products.destroy');
        Route::delete('/products/images/{id}', [AdminController::class, 'productImageDestroy'])->name('products.images.destroy');
        Route::delete('/products/{id}/main-image', [AdminController::class, 'productMainImageDestroy'])->name('products.mainImage.destroy');

        // Orders
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AdminController::class, 'orderShow'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminController::class, 'orderUpdateStatus'])->name('orders.updateStatus');



        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');


        // Coupons
        Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons');
        Route::post('/coupons', [AdminController::class, 'couponStore'])->name('coupons.store');
        Route::delete('/coupons/{id}', [AdminController::class, 'couponDestroy'])->name('coupons.destroy');
    });
});
