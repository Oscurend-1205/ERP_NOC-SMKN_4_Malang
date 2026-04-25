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
|
| Superadmin : Akses penuh (Dashboard, Data Master, Data Barang, dll)
| Admin      : Akses terbatas (Data Barang, Mutasi Barang) tanpa Data Master
|
*/

// Authentication
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Setup (Via URL)
Route::get('setup-password', [AuthController::class, 'showPasswordSetup'])->name('password.setup');
Route::post('setup-password', [AuthController::class, 'updatePassword']);

// ============================================================
// Protected Routes (harus login)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // --------------------------------------------------------
    // SUPERADMIN ONLY - Data Master & Pengguna
    // --------------------------------------------------------
    Route::middleware(['role:Superadmin'])->group(function () {
        // Kategori Barang (Data Master)
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Lokasi Laboratorium (Data Master)
        Route::resource('locations', LocationController::class)->except(['show']);

        // Manajemen Pengguna (hanya Superadmin yang bisa tambah)
        Route::post('data-pengguna', [UserController::class, 'store'])->name('users.store');
    });

    // --------------------------------------------------------
    // SUPERADMIN & ADMIN - Dashboard, Barang & Mutasi
    // --------------------------------------------------------
    Route::middleware(['role:Superadmin,Admin'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Pinjaman Barang (Aksi dari Dashboard)
        Route::post('pinjaman', [ItemMovementController::class, 'storeLoan'])->name('movements.loan');
        
        // Lihat Data Pengguna
        Route::get('data-pengguna', [UserController::class, 'index'])->name('users.index');

        // Barang Elektronik
        Route::resource('items', ItemController::class);

        // Pergerakan Barang
        Route::resource('movements', ItemMovementController::class)->only(['index', 'create', 'store']);
    });
});
