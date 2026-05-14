<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

// Public/Semi-Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/faq', function () { return view('faq'); })->name('faq');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/search-suggestions', [ProductController::class, 'suggestions'])->name('api.search.suggestions');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // Fallback to prevent 404 on manual GET or link click

// Password Reset
Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'show'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/forgot-password/verify-otp', [\App\Http\Controllers\ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/reset-password/{token}', [\App\Http\Controllers\ResetPasswordController::class, 'show'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification
Route::get('/verify-email', [\App\Http\Controllers\VerificationController::class, 'show'])->middleware('auth')->name('verification.notice');
Route::post('/verify-email', [\App\Http\Controllers\VerificationController::class, 'verify'])->middleware(['auth', 'throttle:6,1'])->name('verification.verify');
Route::post('/verify-email/resend', [\App\Http\Controllers\VerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.process');
    Route::get('/checkout/verify', [OrderController::class, 'showVerifyOtp'])->name('checkout.verify');
    Route::post('/checkout/verify', [OrderController::class, 'verifyOtp'])->name('checkout.verify.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/confirmation/{id}', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::post('/orders/{id}/cancel', [\App\Http\Controllers\CancellationController::class, 'cancelByUser'])->name('orders.cancel');
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
        Route::post('/products/{id}', [AdminController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'productDestroy'])->name('products.destroy');
        Route::delete('/products/images/{id}', [AdminController::class, 'productImageDestroy'])->name('products.images.destroy');
        Route::delete('/products/{id}/main-image', [AdminController::class, 'productMainImageDestroy'])->name('products.mainImage.destroy');

        // Orders
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AdminController::class, 'orderShow'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminController::class, 'orderUpdateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{id}/cancel', [\App\Http\Controllers\CancellationController::class, 'cancelByAdmin'])->name('orders.cancel');



        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('users.show');
        Route::patch('/users/{id}/promote', [AdminController::class, 'userPromote'])->name('users.promote');
        Route::patch('/users/{id}/demote', [AdminController::class, 'userDemote'])->name('users.demote');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');


        // Coupons
        Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons');
        Route::post('/coupons', [AdminController::class, 'couponStore'])->name('coupons.store');
        Route::delete('/coupons/{id}', [AdminController::class, 'couponDestroy'])->name('coupons.destroy');

        // Audit Logs (Admin/Owner Only)
        Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    });
});
