<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MercadoLivreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/auth', [MercadoLivreController::class, 'getAuthorizationUrl'])->name('getAuthorizationUrl');
Route::get('/callback', [MercadoLivreController::class, 'callback'])->name('callback');
Route::get('/token', [MercadoLivreController::class, 'getAccessToken'])->name('getAccessToken');

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/product/attributes/{categoryId}', [ProductController::class, 'getAttributes']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
