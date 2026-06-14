<?php
/**
 * Script untuk menghapus (truncate) seluruh data di tabel database
 * dan me-reset sistem ke keadaan awal (fresh) di production.
 * PENTING: Hapus file ini setelah selesai digunakan!
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Proteksi Sederhana (Opsional tapi disarankan, Anda bisa mengubah password ini)
$secretKey = 'reset123'; 

echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>";
echo "<h2 style='color: #dc2626;'>Database Reset Tool (Production)</h2>";

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    echo "<div style='background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; color: #991b1b;'>";
    echo "<b>Akses Ditolak!</b><br>Gunakan parameter key yang benar di URL untuk mengeksekusi script ini.<br>";
    echo "Contoh: <code>?key=reset123</code>";
    echo "</div>";
    echo "</div>";
    exit;
}

try {
    echo "<p>Memulai proses reset database...</p>";
    echo "<ul style='color: #4b5563;'>";

    // 1. Disable Foreign Key Checks
    Schema::disableForeignKeyConstraints();
    echo "<li>✔️ Foreign Key Checks dinonaktifkan.</li>";

    // 2. Daftar semua tabel aplikasi (kecuali migrations)
    $tables = [
        'item_movements',
        'peminjaman',
        'scan_sessions',
        'items',
        'categories',
        'locations',
        'suppliers',
        'kondisi_barangs',
        'asal_barangs',
        'jurusans',
        'users',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens'
    ];

    // 3. Truncate (Kosongkan) setiap tabel
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            DB::table($table)->truncate();
            echo "<li>✔️ Tabel <b>$table</b> berhasil dikosongkan.</li>";
        }
    }

    // 4. Enable Foreign Key Checks
    Schema::enableForeignKeyConstraints();
    echo "<li>✔️ Foreign Key Checks diaktifkan kembali.</li>";

    echo "</ul>";
    
    echo "<div style='background: #f0fdf4; border-left: 4px solid #22c55e; padding: 15px; color: #166534; margin: 20px 0;'>";
    echo "<b>✅ Database berhasil di-reset sepenuhnya!</b> Semua data telah dihapus.";
    echo "</div>";

    // 5. Buat ulang User Admin Dasar agar tetap bisa login
    echo "<h3>Membuat ulang user default...</h3>";
    
    $superadmin = User::create([
        'user_code' => 'USR-001',
        'name' => 'Super Admin NOC',
        'username' => 'superadmin',
        'email' => 'superadmin@noc.smkn4malang.sch.id',
        'password' => Hash::make('Superadmin2026'),
        'role' => 'Superadmin',
        'is_active' => true,
    ]);

    $admin = User::create([
        'user_code' => 'USR-002',
        'name' => 'Admin NOC',
        'username' => 'admin',
        'email' => 'admin@noc.smkn4malang.sch.id',
        'password' => Hash::make('Admin2026'),
        'role' => 'Admin',
        'is_active' => true,
    ]);

    echo "<ul style='color: #4b5563;'>";
    echo "<li>✔️ Akun <b>Superadmin</b> dibuat ulang.</li>";
    echo "<li>✔️ Akun <b>Admin</b> dibuat ulang.</li>";
    echo "</ul>";

    // 6. Jalankan Seeder Dummy jika parameter di-set
    if (isset($_GET['seed']) && $_GET['seed'] == 'true') {
        echo "<h3>Menambahkan Dummy Data...</h3>";
        try {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DummyDataSeeder']);
            echo "<div style='background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; color: #1e3a8a; margin: 20px 0;'>";
            echo "<b>✅ Dummy Data berhasil ditambahkan!</b> Ratusan data master, inventory, user, dan transaksi telah dibuat.";
            echo "</div>";
        } catch (\Exception $se) {
            echo "<div style='background: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; color: #92400e; margin: 20px 0;'>";
            echo "<b>⚠️ Gagal menambahkan dummy data:</b><br>" . $se->getMessage();
            echo "</div>";
        }
    }

    echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";
    echo "<p>🎉 Selesai! Sistem sekarang dalam keadaan bersih (fresh) seperti baru di-install.</p>";
    
    if (!isset($_GET['seed']) || $_GET['seed'] != 'true') {
        echo "<a href='?key={$secretKey}&seed=true' style='display: inline-block; padding: 10px 15px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Beri Dummy Data</a>";
    }
    
    echo "<a href='/' style='display: inline-block; padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Beranda</a>";
    echo "<p style='color: #dc2626; font-size: 12px; margin-top: 20px; font-weight: bold;'>⚠️ PENTING: Segera hapus file <b>public/mod-db.php</b> ini dari File Manager hosting Anda untuk mencegah orang lain mereset database Anda!</p>";

} catch (\Exception $e) {
    echo "<div style='background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; color: #991b1b; margin: 20px 0;'>";
    echo "<b>❌ Terjadi Kesalahan:</b><br>" . $e->getMessage();
    echo "</div>";
    
    // Pastikan foreign key check dihidupkan kembali jika terjadi error
    Schema::enableForeignKeyConstraints();
}

echo "</div>";
