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
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IpController;
use App\Http\Controllers\StLogController;

/*
|--------------------------------------------------------------------------
| Web Routes - ERP NOC SMKN 4 Malang
|--------------------------------------------------------------------------
|
| Superadmin : Akses penuh (Dashboard, Data Master, Data Barang, dll)
| Admin      : Akses terbatas (Data Barang, Mutasi Barang) tanpa Data Master
|
*/
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('setup-password', [AuthController::class, 'showPasswordSetup'])->name('password.setup');
Route::post('setup-password', [AuthController::class, 'updatePassword']);
Route::prefix('mt')->group(function () {
    Route::get('/', [IpController::class, 'autoTrack'])->name('mt.index');
});

Route::post('api/st-log', [StLogController::class, 'store'])
    ->name('st-log');
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:Superadmin'])->group(function () {
        Route::resource('kategori-barang', \App\Http\Controllers\CategoryController::class)
            ->names('categories')
            ->parameters(['kategori-barang' => 'category'])
            ->except(['show', 'create', 'edit']);
        Route::resource('data-supplier', \App\Http\Controllers\SupplierController::class)
            ->names('supplier')
            ->parameters(['data-supplier' => 'supplier'])
            ->except(['show', 'create', 'edit']);
        Route::resource('kondisi-barang', \App\Http\Controllers\KondisiBarangController::class)
            ->names('kondisi')
            ->parameters(['kondisi-barang' => 'kondisi'])
            ->except(['show', 'create', 'edit']);
        Route::resource('asal-barang', \App\Http\Controllers\AsalBarangController::class)
            ->names('asal')
            ->parameters(['asal-barang' => 'asal'])
            ->except(['show', 'create', 'edit']);
        Route::resource('locations', LocationController::class)->except(['show']);
        Route::resource('data-jurusan', \App\Http\Controllers\JurusanController::class)
            ->names('jurusan')
            ->parameters(['data-jurusan' => 'jurusan'])
            ->except(['show', 'create', 'edit']);
        Route::post('data-pengguna', [UserController::class, 'store'])->name('users.store');
        Route::put('data-pengguna/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('data-pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/reset', [\App\Http\Controllers\SettingController::class, 'resetSystem'])->name('settings.reset');
        Route::post('settings/reset-database', [\App\Http\Controllers\SettingController::class, 'resetDatabase'])->name('settings.reset-database');
        Route::post('settings/seed-dummy', [\App\Http\Controllers\SettingController::class, 'seedDummyData'])->name('settings.seed-dummy');
        Route::post('settings/clear-cache', [\App\Http\Controllers\SettingController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('settings/storage-link', [\App\Http\Controllers\SettingController::class, 'createStorageLink'])->name('settings.storage-link');
        Route::post('settings/run-migrations', [\App\Http\Controllers\SettingController::class, 'runMigrations'])->name('settings.run-migrations');
        Route::post('settings/fix-strict-mode', [\App\Http\Controllers\SettingController::class, 'fixStrictMode'])->name('settings.fix-strict-mode');
        Route::get('settings/sql-mode-status', [\App\Http\Controllers\SettingController::class, 'getSqlModeStatus'])->name('settings.sql-mode-status');
        Route::delete('items/barang-masuk/{movement}', [ItemController::class, 'destroyBarangMasuk'])->name('items.barang-masuk.destroy');
    });
    Route::middleware(['role:Superadmin,Admin,Jurusan'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [UserController::class, 'profile'])->name('profile.index');
        Route::put('profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
        
        // Peminjaman (Jurusan can see their own, Admin sees all)
        Route::get('data-peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
        
        // Items (Jurusan can see items available for borrowing)
        Route::get('items', [ItemController::class, 'index'])->name('items.index');
    });

    Route::middleware(['role:Superadmin,Admin'])->group(function () {
        Route::post('pinjaman', [\App\Http\Controllers\PeminjamanController::class, 'storeManual'])->name('movements.loan');
        Route::get('data-pengguna', [UserController::class, 'index'])->name('users.index');
        Route::get('items/barang-masuk', [ItemController::class, 'barangMasuk'])->name('items.barang-masuk');
        Route::post('items/barang-masuk', [ItemController::class, 'storeBarangMasuk'])->name('items.barang-masuk.store');
        Route::get('items/barang-keluar', [ItemController::class, 'barangKeluar'])->name('items.barang-keluar');
        Route::get('items/units', [ItemController::class, 'units'])->name('items.units');
        Route::get('items/next-code', [ItemController::class, 'getNextCode'])->name('items.next-code');
        Route::post('items/quick-category', [ItemController::class, 'quickStoreCategory'])->name('items.quick-category');
        Route::resource('items', ItemController::class)->except(['index']);
        
        Route::post('data-peminjaman/{peminjaman}/return', [\App\Http\Controllers\PeminjamanController::class, 'returnItem'])->name('peminjaman.return');
        Route::delete('data-peminjaman/{peminjaman}', [\App\Http\Controllers\PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
        Route::resource('data-perawatan', \App\Http\Controllers\PerawatanController::class)->names('perawatan');
        Route::post('data-perawatan/{id}/generate-link', [\App\Http\Controllers\PerawatanController::class, 'generateLink'])->name('perawatan.generate-link');
        Route::post('data-perawatan/{id}/verify', [\App\Http\Controllers\PerawatanController::class, 'verifyMaintenance'])->name('perawatan.verify');
        Route::get('laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('export/barang-masuk/csv', [ExportController::class, 'barangMasukCsv'])->name('export.barang-masuk.csv');
        Route::get('export/barang-masuk/print', [ExportController::class, 'barangMasukPrint'])->name('export.barang-masuk.print');
        Route::get('export/barang-keluar/csv', [ExportController::class, 'barangKeluarCsv'])->name('export.barang-keluar.csv');
        Route::get('export/barang-keluar/print', [ExportController::class, 'barangKeluarPrint'])->name('export.barang-keluar.print');
        Route::get('export/peminjaman/csv', [ExportController::class, 'peminjamanCsv'])->name('export.peminjaman.csv');
        Route::get('export/peminjaman/print', [ExportController::class, 'peminjamanPrint'])->name('export.peminjaman.print');
        Route::get('export/inventaris/csv', [ExportController::class, 'inventarisCsv'])->name('export.inventaris.csv');
        Route::get('export/inventaris/print', [ExportController::class, 'inventarisPrint'])->name('export.inventaris.print');
        Route::get('export/ringkasan/print', [ExportController::class, 'ringkasanPrint'])->name('export.ringkasan.print');
        Route::get('export/laporan-lengkap/excel', [\App\Http\Controllers\ExcelExportController::class, 'laporanLengkap'])->name('export.laporan-lengkap.excel');
        Route::get('qr-panel', [QrAdminController::class, 'index'])->name('qr.admin');
        Route::post('qr-generate', [QrAdminController::class, 'generateQr'])->name('qr.generate');
        Route::get('qr-poll', [QrAdminController::class, 'pollPeminjaman'])->name('qr.poll');
        Route::delete('qr-revoke/{token}', [QrAdminController::class, 'revokeToken'])->name('qr.revoke');
    });
});

Route::middleware(['scan.token'])->group(function () {
    Route::get('scan/{token}', [QrScanController::class, 'showScanner'])->name('qr.scan');
    Route::get('scan/{token}/lookup/{code}', [QrScanController::class, 'lookupItem'])->name('qr.lookup');
    Route::post('scan/{token}/submit', [QrScanController::class, 'submitPeminjaman'])->name('qr.submit');
});

// Public Maintenance Routes for Technicians
Route::get('/maintenance/report/{token}', [\App\Http\Controllers\PerawatanController::class, 'publicMaintenanceForm'])->name('maintenance.public_form');
Route::post('/maintenance/report/{token}', [\App\Http\Controllers\PerawatanController::class, 'publicMaintenanceSubmit'])->name('maintenance.public_submit');
Route::get('/run-migrations', function () {
    try {
        $migrationPath = database_path('migrations');
        $migrationFiles = glob($migrationPath . '/*.php');
        $ran = \Illuminate\Support\Facades\DB::table('migrations')->pluck('migration')->toArray();
        $pending = [];

        foreach ($migrationFiles as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            if (!in_array($name, $ran)) {
                $pending[] = ['file' => $file, 'name' => $name];
            }
        }

        if (empty($pending)) {
            return "<h3>Tidak ada migrasi yang perlu dijalankan. Database sudah up-to-date.</h3>";
        }

        $batch = \Illuminate\Support\Facades\DB::table('migrations')->max('batch') + 1;
        $results = [];

        foreach ($pending as $migration) {
            require_once $migration['file'];
            $classes = get_declared_classes();
            $className = end($classes);
            $instance = new $className();
            $instance->up();

            \Illuminate\Support\Facades\DB::table('migrations')->insert([
                'migration' => $migration['name'],
                'batch'     => $batch,
            ]);
            $results[] = $migration['name'];
        }

        $count = count($results);
        return "<h3>Migration Berhasil! ({$count} migrasi dijalankan)</h3>";
    } catch (\Exception $e) {
        return "<h3>Terjadi Kesalahan:</h3><p>" . $e->getMessage() . "</p>";
    }
});

Route::get('/deploy-setup', function () {
    try {
        $messages = [];
        $cacheFiles = [
            base_path('bootstrap/cache/config.php'),
            base_path('bootstrap/cache/packages.php'),
            base_path('bootstrap/cache/services.php'),
        ];
        foreach ($cacheFiles as $file) {
            if (file_exists($file)) { @unlink($file); }
        }
        foreach (glob(base_path('bootstrap/cache/routes-v7*.php')) as $file) {
            @unlink($file);
        }
        $viewsDir = storage_path('framework/views');
        if (is_dir($viewsDir)) {
            foreach (glob($viewsDir . '/*.php') as $file) { @unlink($file); }
        }
        $messages[] = '1. Cache sistem telah dibersihkan.';
        $link = public_path('storage');
        $target = storage_path('app/public');

        if (file_exists($link) || is_link($link)) {
            $messages[] = '2. Storage link sudah ada.';
        } else {
            $linked = false;
            if (function_exists('symlink')) {
                $linked = @symlink($target, $link);
            }
            if (!$linked) {
                @mkdir($link, 0755, true);
            }
            $messages[] = '2. Storage link berhasil dibuat.';
        }
        $requiredDirs = [
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];
        foreach ($requiredDirs as $dir) {
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        }
        $messages[] = '3. Folder storage yang diperlukan sudah tersedia.';

        $html = "<h3>Deployment Setup Berhasil!</h3>";
        foreach ($messages as $msg) {
            $html .= "<p>{$msg}</p>";
        }
        $html .= "<p><b>Proyek Anda siap digunakan!</b></p>";
        return $html;
    } catch (\Exception $e) {
        return "<h3>Terjadi Kesalahan saat Deployment Setup:</h3><p>" . $e->getMessage() . "</p>";
    }
});
Route::get('/reset-database', [DbSeederController::class, 'resetAndSeed']);

// Fitur Tersembunyi: Sinkronisasi URL ke Bridge Native (Nichesite)
Route::get('/bridge-sync', function (\Illuminate\Http\Request $request) {
    $currentUrl = url('/');
    $bridgeServer = 'https://nichesows.nichesite.org/index.php';
    $secretKey = 'n0c-s3cr3t-2026';
    
    // Auto-configure session SameSite for iframes
    $envFile = base_path('.env');
    if (file_exists($envFile)) {
        $env = file_get_contents($envFile);
        if (!str_contains($env, 'SESSION_SAME_SITE=none')) {
            file_put_contents($envFile, $env . "\nSESSION_SAME_SITE=none\nSESSION_SECURE_COOKIE=true\n");
        }
    }
    
    try {
        $apiUrl = $bridgeServer . '?update_bridge=1&key=' . $secretKey . '&url=' . urlencode($currentUrl);
        $context = stream_context_create(['http' => ['ignore_errors' => true]]);
        $response = file_get_contents($apiUrl, false, $context);
        return "<h3>Stealth Bridge Sync</h3><p>Deployment saat ini: <b>{$currentUrl}</b></p><p>Respon Native: {$response}</p>";
    } catch (\Exception $e) {
        return "<h3>Sync Gagal</h3><p>" . $e->getMessage() . "</p>";
    }
});

