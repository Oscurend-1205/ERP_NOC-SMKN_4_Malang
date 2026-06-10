<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemMovementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QrAdminController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\DbSeederController;

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
        Route::resource('kategori-barang', \App\Http\Controllers\CategoryController::class)
            ->names('categories')
            ->parameters(['kategori-barang' => 'category'])
            ->except(['show', 'create', 'edit']);

        // Data Supplier (Data Master)
        Route::resource('data-supplier', \App\Http\Controllers\SupplierController::class)
            ->names('supplier')
            ->parameters(['data-supplier' => 'supplier'])
            ->except(['show', 'create', 'edit']);

        // Kondisi Barang (Data Master)
        Route::resource('kondisi-barang', \App\Http\Controllers\KondisiBarangController::class)
            ->names('kondisi')
            ->parameters(['kondisi-barang' => 'kondisi'])
            ->except(['show', 'create', 'edit']);

        // Asal Barang (Data Master)
        Route::resource('asal-barang', \App\Http\Controllers\AsalBarangController::class)
            ->names('asal')
            ->parameters(['asal-barang' => 'asal'])
            ->except(['show', 'create', 'edit']);

        // Lokasi Laboratorium (Data Master / Data Ruangan)
        Route::resource('locations', LocationController::class)->except(['show']);

        // Data Jurusan (Data Master)
        Route::resource('data-jurusan', \App\Http\Controllers\JurusanController::class)
            ->names('jurusan')
            ->parameters(['data-jurusan' => 'jurusan'])
            ->except(['show', 'create', 'edit']);

        // Manajemen Pengguna (hanya Superadmin yang bisa tambah/edit/hapus)
        Route::post('data-pengguna', [UserController::class, 'store'])->name('users.store');
        Route::put('data-pengguna/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('data-pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Pengaturan Sistem
        Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/reset', [\App\Http\Controllers\SettingController::class, 'resetSystem'])->name('settings.reset');

        // Hapus Barang Masuk (Superadmin only)
        Route::delete('items/barang-masuk/{movement}', [ItemController::class, 'destroyBarangMasuk'])->name('items.barang-masuk.destroy');
    });

    // --------------------------------------------------------
    // SUPERADMIN & ADMIN - Dashboard, Barang & Mutasi
    // --------------------------------------------------------
    Route::middleware(['role:Superadmin,Admin'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Pinjaman Barang (Aksi dari Dashboard/Modal)
        Route::post('pinjaman', [\App\Http\Controllers\PeminjamanController::class, 'storeManual'])->name('movements.loan');
        
        // Lihat Data Pengguna
        Route::get('data-pengguna', [UserController::class, 'index'])->name('users.index');

        // Input Barang via Scanner
        Route::get('items/scan-input', [ItemController::class, 'scanInput'])->name('items.scan-input');
        Route::post('items/scan-input', [ItemController::class, 'storeScanInput'])->name('items.store-scan');
        
        // Barang Masuk & Keluar
        Route::get('items/barang-masuk', [ItemController::class, 'barangMasuk'])->name('items.barang-masuk');
        Route::post('items/barang-masuk', [ItemController::class, 'storeBarangMasuk'])->name('items.barang-masuk.store');
        Route::get('items/barang-keluar', [ItemController::class, 'barangKeluar'])->name('items.barang-keluar');
        
        // Barang Elektronik
        Route::get('items/units', [ItemController::class, 'units'])->name('items.units');
        Route::resource('items', ItemController::class);

        // Pergerakan Barang (Mutasi) dihapus sesuai permintaan
        // Route::resource('movements', ItemMovementController::class)->only(['index', 'create', 'store']);

        // Data Peminjaman (Riwayat Detail)
        Route::get('data-peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('data-peminjaman/{peminjaman}/return', [\App\Http\Controllers\PeminjamanController::class, 'returnItem'])->name('peminjaman.return');
        Route::delete('data-peminjaman/{peminjaman}', [\App\Http\Controllers\PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

        // --------------------------------------------------------
        // QR LENDING SYSTEM - Admin Panel
        // --------------------------------------------------------
        Route::get('qr-panel', [QrAdminController::class, 'index'])->name('qr.admin');
        Route::post('qr-generate', [QrAdminController::class, 'generateQr'])->name('qr.generate');
        Route::get('qr-poll', [QrAdminController::class, 'pollPeminjaman'])->name('qr.poll');
        Route::delete('qr-revoke/{token}', [QrAdminController::class, 'revokeToken'])->name('qr.revoke');
    });
});

// ============================================================
// PUBLIC ROUTES - QR Scan (Tidak Perlu Login, Dilindungi Token)
// ============================================================
Route::middleware(['scan.token'])->group(function () {
    Route::get('scan/{token}', [QrScanController::class, 'showScanner'])->name('qr.scan');
    Route::get('scan/{token}/lookup/{code}', [QrScanController::class, 'lookupItem'])->name('qr.lookup');
    Route::post('scan/{token}/submit', [QrScanController::class, 'submitPeminjaman'])->name('qr.submit');
});

// ============================================================
// DEPLOYMENT ASSISTANT (Hanya untuk Shared Hosting / InfinityFree)
// ============================================================
Route::get('/deploy-setup', function () {
    try {
        // Hapus cache lama
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        
        // Buat Storage Link jika belum ada
        if (!file_exists(public_path('storage'))) {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
        }
        
        return "<h3>Deployment Setup Berhasil!</h3>
                <p>1. Cache sistem telah dibersihkan.</p>
                <p>2. Link Storage ke Public berhasil dibuat.</p>
                <p>Proyek Anda siap digunakan!";
    } catch (\Exception $e) {
        return "<h3>Terjadi Kesalahan saat Deployment Setup:</h3><p>" . $e->getMessage() . "</p>";
    }
});

// ============================================================
// DATABASE RESET & SEEDER (Bisa diakses langsung via URL)
// ============================================================
Route::get('/reset-database', [DbSeederController::class, 'resetAndSeed']);
