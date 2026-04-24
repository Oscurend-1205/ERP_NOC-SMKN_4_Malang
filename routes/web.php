<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemMovementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - ERP NOC SMKN 4 Malang
|--------------------------------------------------------------------------
*/

// Authentication
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Setup (Via URL)
Route::get('setup-password', [AuthController::class, 'showPasswordSetup'])->name('password.setup');
Route::post('setup-password', [AuthController::class, 'updatePassword']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori Barang
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Lokasi Laboratorium
    Route::resource('locations', LocationController::class)->except(['show']);

    // Barang Elektronik
    Route::resource('items', ItemController::class);

    // Pergerakan Barang
    Route::resource('movements', ItemMovementController::class)->only(['index', 'create', 'store']);

    // Manajemen Pengguna
    Route::resource('data-pengguna', UserController::class)->names([
        'index' => 'users.index',
        'store' => 'users.store'
    ])->only(['index', 'store']);
});
