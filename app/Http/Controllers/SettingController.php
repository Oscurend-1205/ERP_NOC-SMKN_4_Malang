<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Gather system stats for the info card
        $stats = [
            'items'       => Schema::hasTable('items') ? DB::table('items')->count() : 0,
            'categories'  => Schema::hasTable('categories') ? DB::table('categories')->count() : 0,
            'locations'   => Schema::hasTable('locations') ? DB::table('locations')->count() : 0,
            'users'       => Schema::hasTable('users') ? DB::table('users')->count() : 0,
            'peminjaman'  => Schema::hasTable('peminjaman') ? DB::table('peminjaman')->count() : 0,
            'movements'   => Schema::hasTable('item_movements') ? DB::table('item_movements')->count() : 0,
            'perawatans'  => Schema::hasTable('perawatans') ? DB::table('perawatans')->count() : 0,
        ];

        $systemInfo = [
            'php_version'     => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_driver'       => config('database.default'),
            'db_name'         => config('database.connections.' . config('database.default') . '.database', '-'),
            'app_env'         => config('app.env'),
            'storage_linked'  => file_exists(public_path('storage')),
        ];

        return view('settings.index', compact('stats', 'systemInfo'));
    }

    /**
     * Reset the system (truncate all tables + re-create admin accounts).
     * Mirrors the logic from mod-db.php but integrated into the app.
     */
    public function resetDatabase(Request $request)
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            Schema::disableForeignKeyConstraints();

            $tables = [
                'perawatans', 'item_movements', 'peminjaman', 'scan_sessions',
                'items', 'categories', 'locations',
                'suppliers', 'kondisi_barangs', 'asal_barangs', 'jurusans',
                'users', 'sessions', 'cache', 'cache_locks',
                'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens',
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            Schema::enableForeignKeyConstraints();

            // Re-create default admin accounts
            User::create([
                'user_code' => 'USR-001',
                'name'      => 'Super Admin NOC',
                'username'  => 'superadmin',
                'email'     => 'superadmin@noc.smkn4malang.sch.id',
                'password'  => Hash::make('Superadmin2026'),
                'role'      => 'Superadmin',
                'is_active' => true,
            ]);

            User::create([
                'user_code' => 'USR-002',
                'name'      => 'Admin NOC',
                'username'  => 'admin',
                'email'     => 'admin@noc.smkn4malang.sch.id',
                'password'  => Hash::make('Admin2026'),
                'role'      => 'Admin',
                'is_active' => true,
            ]);

            // Logout current user since all sessions are gone
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Database berhasil di-reset. Semua data telah dihapus dan akun default dibuat ulang. Silakan login dengan akun Superadmin (superadmin / Superadmin2026).');
        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Gagal mereset database: ' . $e->getMessage());
        }
    }

    /**
     * Seed dummy data into the current database.
     */
    public function seedDummyData()
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            // Jalankan seeder secara langsung tanpa Artisan (menghindari exec())
            $seeder = new \Database\Seeders\DummyDataSeeder();
            $seeder->run();
            return redirect()->back()->with('success', 'Dummy data berhasil ditambahkan! Data master, inventaris, pengguna, dan transaksi contoh telah dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan dummy data: ' . $e->getMessage());
        }
    }

    /**
     * Reset system via migrate:fresh --seed (full schema rebuild).
     */
    public function resetSystem(Request $request)
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            // Gunakan resetDatabase + seedDummyData sebagai pengganti migrate:fresh --seed
            // karena migrate:fresh menggunakan exec() yang diblokir InfinityFree
            Schema::disableForeignKeyConstraints();

            $tables = [
                'perawatans', 'item_movements', 'peminjaman', 'scan_sessions',
                'items', 'categories', 'locations',
                'suppliers', 'kondisi_barangs', 'asal_barangs', 'jurusans',
                'users', 'sessions', 'cache', 'cache_locks',
                'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens',
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            Schema::enableForeignKeyConstraints();

            // Re-seed default accounts
            $seeder = new \Database\Seeders\DatabaseSeeder();
            $seeder->run();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Sistem berhasil di-reset penuh. Silakan login kembali.');
        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mereset sistem: ' . $e->getMessage());
        }
    }

    /**
     * Clear all Laravel caches (config, route, view, app).
     */
    public function clearCache()
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            // Hapus cache secara manual (menghindari Artisan::call yang pakai exec())
            $cleared = [];

            // 1. Hapus config cache
            $configCache = base_path('bootstrap/cache/config.php');
            if (file_exists($configCache)) { @unlink($configCache); $cleared[] = 'config'; }

            // 2. Hapus route cache
            foreach (glob(base_path('bootstrap/cache/routes-v7*.php')) as $file) {
                @unlink($file); $cleared[] = 'routes';
            }

            // 3. Hapus compiled views
            $compiledViews = storage_path('framework/views');
            if (is_dir($compiledViews)) {
                foreach (glob($compiledViews . '/*.php') as $file) {
                    @unlink($file);
                }
                $cleared[] = 'views';
            }

            // 4. Hapus application cache
            $cacheDir = storage_path('framework/cache/data');
            if (is_dir($cacheDir)) {
                $this->deleteDirectoryContents($cacheDir);
                $cleared[] = 'cache';
            }

            // 5. Hapus bootstrap cache files lainnya
            $bootstrapFiles = ['packages.php', 'services.php'];
            foreach ($bootstrapFiles as $bf) {
                $path = base_path('bootstrap/cache/' . $bf);
                if (file_exists($path)) { @unlink($path); }
            }

            $clearedStr = implode(', ', array_unique($cleared)) ?: 'tidak ada cache';
            return redirect()->back()->with('success', "Cache sistem berhasil dibersihkan: {$clearedStr}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Delete all files/subdirectories inside a directory (keep the directory itself).
     */
    private function deleteDirectoryContents($dir)
    {
        if (!is_dir($dir)) return;
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if ($item === '.gitignore' || $item === '.gitkeep') continue;
            if (is_dir($path)) {
                $this->deleteDirectoryContents($path);
                @rmdir($path);
            } else {
                @unlink($path);
            }
        }
    }

    /**
     * Create storage symlink if missing.
     */
    public function createStorageLink()
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            $link = public_path('storage');
            $target = storage_path('app/public');

            if (file_exists($link) || is_link($link)) {
                return redirect()->back()->with('success', 'Storage link sudah ada.');
            }

            // Coba buat symlink secara langsung (tanpa Artisan yang pakai exec())
            if (function_exists('symlink')) {
                $result = @symlink($target, $link);
                if ($result) {
                    return redirect()->back()->with('success', 'Storage link berhasil dibuat (symlink).');
                }
            }

            // Fallback: Jika symlink gagal (InfinityFree blokir symlink),
            // buat folder fisik dan copy isinya
            if (!is_dir($link)) {
                @mkdir($link, 0755, true);
            }

            // Copy isi storage/app/public ke public/storage
            if (is_dir($target)) {
                $this->copyDirectory($target, $link);
            }

            return redirect()->back()->with('success', 'Storage link berhasil dibuat (copy folder). Catatan: Pada shared hosting, file upload baru perlu disinkronkan manual.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat storage link: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Copy directory contents recursively.
     */
    private function copyDirectory($source, $dest)
    {
        if (!is_dir($dest)) {
            @mkdir($dest, 0755, true);
        }
        $items = array_diff(scandir($source), ['.', '..']);
        foreach ($items as $item) {
            $srcPath = $source . DIRECTORY_SEPARATOR . $item;
            $dstPath = $dest . DIRECTORY_SEPARATOR . $item;
            if (is_dir($srcPath)) {
                $this->copyDirectory($srcPath, $dstPath);
            } else {
                @copy($srcPath, $dstPath);
            }
        }
    }

    /**
     * Run pending database migrations.
     */
    public function runMigrations()
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        try {
            // Jalankan migrasi secara manual tanpa Artisan (menghindari exec())
            $migrationPath = database_path('migrations');
            $migrationFiles = glob($migrationPath . '/*.php');
            $ran = DB::table('migrations')->pluck('migration')->toArray();
            $pending = [];

            foreach ($migrationFiles as $file) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                if (!in_array($name, $ran)) {
                    $pending[] = ['file' => $file, 'name' => $name];
                }
            }

            if (empty($pending)) {
                return redirect()->back()->with('success', 'Tidak ada migrasi yang perlu dijalankan. Database sudah up-to-date.');
            }

            $batch = DB::table('migrations')->max('batch') + 1;
            $executed = [];

            foreach ($pending as $migration) {
                require_once $migration['file'];
                $classes = get_declared_classes();
                $className = end($classes);
                $instance = new $className();
                $instance->up();

                DB::table('migrations')->insert([
                    'migration' => $migration['name'],
                    'batch'     => $batch,
                ]);
                $executed[] = $migration['name'];
            }

            $count = count($executed);
            return redirect()->back()->with('success', "{$count} migrasi berhasil dijalankan.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menjalankan migrasi: ' . $e->getMessage());
        }
    }

    /**
     * Get current MySQL sql_mode status (AJAX).
     */
    public function getSqlModeStatus()
    {
        if (Auth::user()->role !== 'Superadmin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $driver = config('database.default');

            if (!in_array($driver, ['mysql', 'mariadb'])) {
                return response()->json([
                    'driver'       => $driver,
                    'supported'    => false,
                    'message'      => 'Fitur ini hanya tersedia untuk database MySQL/MariaDB.',
                ]);
            }

            $result = DB::selectOne("SELECT @@GLOBAL.sql_mode AS global_mode, @@SESSION.sql_mode AS session_mode");

            $globalMode  = $result->global_mode ?? '';
            $sessionMode = $result->session_mode ?? '';
            $hasIssue    = (stripos($globalMode, 'ONLY_FULL_GROUP_BY') !== false) ||
                           (stripos($sessionMode, 'ONLY_FULL_GROUP_BY') !== false);

            $laravelStrict = config('database.connections.' . $driver . '.strict', true);

            return response()->json([
                'supported'      => true,
                'driver'         => $driver,
                'global_mode'    => $globalMode,
                'session_mode'   => $sessionMode,
                'has_issue'      => $hasIssue,
                'laravel_strict' => $laravelStrict,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fix MySQL ONLY_FULL_GROUP_BY strict mode issue.
     * Removes ONLY_FULL_GROUP_BY from GLOBAL and SESSION sql_mode.
     */
    public function fixStrictMode()
    {
        if (Auth::user()->role !== 'Superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        $driver = config('database.default');

        if (!in_array($driver, ['mysql', 'mariadb'])) {
            return redirect()->back()->with('error', 'Fitur ini hanya tersedia untuk database MySQL/MariaDB. Driver saat ini: ' . $driver);
        }

        try {
            $result = DB::selectOne("SELECT @@GLOBAL.sql_mode AS global_mode, @@SESSION.sql_mode AS session_mode");

            $globalMode  = $result->global_mode ?? '';
            $sessionMode = $result->session_mode ?? '';
            $messages    = [];

            // Check if fix is needed
            $globalHasIssue  = (stripos($globalMode, 'ONLY_FULL_GROUP_BY') !== false);
            $sessionHasIssue = (stripos($sessionMode, 'ONLY_FULL_GROUP_BY') !== false);

            if (!$globalHasIssue && !$sessionHasIssue) {
                return redirect()->back()->with('success', 'ONLY_FULL_GROUP_BY sudah tidak aktif. Tidak ada yang perlu diperbaiki.');
            }

            // Remove ONLY_FULL_GROUP_BY from global mode
            if ($globalHasIssue) {
                $newGlobalMode = implode(',', array_filter(
                    array_map('trim', explode(',', $globalMode)),
                    fn($mode) => strtoupper($mode) !== 'ONLY_FULL_GROUP_BY'
                ));

                try {
                    DB::statement("SET GLOBAL sql_mode = ?", [$newGlobalMode]);
                    $messages[] = 'GLOBAL sql_mode berhasil diubah';
                } catch (\Exception $e) {
                    $messages[] = 'GLOBAL sql_mode tidak bisa diubah (butuh SUPER privilege)';
                }
            }

            // Remove ONLY_FULL_GROUP_BY from session mode
            if ($sessionHasIssue) {
                $newSessionMode = implode(',', array_filter(
                    array_map('trim', explode(',', $sessionMode)),
                    fn($mode) => strtoupper($mode) !== 'ONLY_FULL_GROUP_BY'
                ));

                try {
                    DB::statement("SET SESSION sql_mode = ?", [$newSessionMode]);
                    $messages[] = 'SESSION sql_mode berhasil diubah';
                } catch (\Exception $e) {
                    $messages[] = 'Gagal mengubah SESSION sql_mode: ' . $e->getMessage();
                }
            }

            $summary = implode('. ', $messages) . '.';
            return redirect()->back()->with('success', 'Fix Strict Mode berhasil diterapkan! ' . $summary);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbaiki strict mode: ' . $e->getMessage());
        }
    }
}
