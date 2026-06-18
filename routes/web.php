<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('products.index'))->name('home');

/* ── PUBLIC PRODUCTS ── */
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/',          [ProductController::class, 'index'])->name('index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});

/* ── GUEST ROUTES ── */
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/* ── AUTHENTICATED ROUTES ── */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile',          [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',            [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/',                [CartController::class, 'index'])->name('index');
        Route::post('/checkout',       [CartController::class, 'checkout'])->name('checkout');
        Route::post('/{product}',      [CartController::class, 'add'])->name('add');
        Route::delete('/{productId}',  [CartController::class, 'remove'])->name('remove');
        Route::patch('/{productId}',   [CartController::class, 'updateQty'])->name('updateQty');
        Route::delete('/',             [CartController::class, 'clear'])->name('clear');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/',            [OrderController::class, 'index'])->name('index');
        Route::get('/{id}',        [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
});

/* ── ADMIN ROUTES ── */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    Route::get('/users',      [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');

    Route::get('/orders',                [AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/',                [ProductController::class, 'adminIndex'])->name('index');
        Route::get('/create',          [ProductController::class, 'create'])->name('create');
        Route::post('/',               [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit',  [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}',       [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}',    [ProductController::class, 'destroy'])->name('destroy');
    });
});