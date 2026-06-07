<?php
/**
 * Database Migration Tool untuk InfinityFree
 * 
 * Script ini membaca file SQL dari project dan mengeksekusinya ke database.
 * Letakkan file ini di folder public/ (htdocs/public/migrate.php)
 * Akses via browser: namadomain.com/migrate.php
 * 
 * HAPUS FILE INI SETELAH SELESAI!
 */

ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');

$action = $_GET['action'] ?? '';
$baseDir = dirname(__DIR__);
$sqlFile = $baseDir . '/database/erp_noc_smkn4malang.sql';

// Ambil konfigurasi database dari .env
function parseEnv($baseDir) {
    $envFile = $baseDir . '/.env';
    $config = [];
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($key, $value) = explode('=', $line, 2);
            $config[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }
    return $config;
}

$env = parseEnv($baseDir);
$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';

$message = '';
$status = '';
$dbConnected = false;
$tableList = [];

// Test koneksi
try {
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName}", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    $dbConnected = true;
    
    // Ambil daftar tabel yang sudah ada
    $stmt = $pdo->query("SHOW TABLES");
    $tableList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $dbConnected = false;
    $message = "Gagal koneksi ke database: " . $e->getMessage();
    $status = 'error';
}

// === AKSI: IMPORT FULL SQL ===
if ($action === 'import_full' && $dbConnected) {
    if (!file_exists($sqlFile)) {
        $status = 'error';
        $message = "File SQL tidak ditemukan di: database/erp_noc_smkn4malang.sql";
    } else {
        try {
            $sql = file_get_contents($sqlFile);
            
            // Hilangkan baris CREATE DATABASE dan USE (karena di InfinityFree DB sudah ada)
            $sql = preg_replace('/CREATE DATABASE.*?;\s*/is', '', $sql);
            $sql = preg_replace('/USE `.*?`;\s*/i', '', $sql);
            
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            // Pecah per statement
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            $executed = 0;
            $errors = [];
            
            foreach ($statements as $stmt) {
                $stmt = trim($stmt);
                if (empty($stmt) || $stmt === 'COMMIT') continue;
                // Lewati komentar dan SET statements yang sudah dihandle
                if (preg_match('/^(--|\/\*|SET FOREIGN_KEY_CHECKS|START TRANSACTION|COMMIT)/i', $stmt)) continue;
                
                try {
                    $pdo->exec($stmt);
                    $executed++;
                } catch (PDOException $e) {
                    $errors[] = substr($stmt, 0, 80) . "... → " . $e->getMessage();
                }
            }
            
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            
            $status = 'success';
            $message = "Import selesai! {$executed} statement berhasil dieksekusi.";
            if (count($errors) > 0) {
                $message .= "<br><br><b>Warning (" . count($errors) . " error):</b><br>";
                foreach (array_slice($errors, 0, 10) as $err) {
                    $message .= "<code class='block text-xs mt-1 bg-red-50 p-1 rounded'>" . htmlspecialchars($err) . "</code>";
                }
            }
            
            // Refresh daftar tabel
            $stmt = $pdo->query("SHOW TABLES");
            $tableList = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            $status = 'error';
            $message = "Error saat import: " . $e->getMessage();
        }
    }
}

// === AKSI: DROP ALL TABLES ===
if ($action === 'drop_all' && $dbConnected) {
    try {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $dropped = 0;
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            $dropped++;
        }
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        $status = 'success';
        $message = "Berhasil menghapus {$dropped} tabel dari database.";
        $tableList = [];
    } catch (Exception $e) {
        $status = 'error';
        $message = "Error saat menghapus tabel: " . $e->getMessage();
    }
}

// === AKSI: MIGRATE ONLY (Hanya buat tabel baru tanpa hapus yang lama) ===
if ($action === 'migrate_new' && $dbConnected) {
    if (!file_exists($sqlFile)) {
        $status = 'error';
        $message = "File SQL tidak ditemukan di: database/erp_noc_smkn4malang.sql";
    } else {
        try {
            $sql = file_get_contents($sqlFile);
            $sql = preg_replace('/CREATE DATABASE.*?;\s*/is', '', $sql);
            $sql = preg_replace('/USE `.*?`;\s*/i', '', $sql);
            
            // Hanya ambil CREATE TABLE statements (tanpa DROP)
            preg_match_all('/CREATE TABLE(?:\s+IF NOT EXISTS)?\s+`(\w+)`/i', $sql, $matches);
            $tablesToCreate = $matches[1] ?? [];
            
            // Cek tabel yang sudah ada
            $stmt = $pdo->query("SHOW TABLES");
            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            $created = 0;
            $skipped = 0;
            $errors = [];
            
            foreach ($tablesToCreate as $tableName) {
                if (in_array($tableName, $existingTables)) {
                    $skipped++;
                    continue;
                }
                
                // Ambil CREATE TABLE statement lengkap untuk tabel ini
                $pattern = '/CREATE TABLE(?:\s+IF NOT EXISTS)?\s+`' . preg_quote($tableName, '/') . '`\s*\(.*?\)\s*ENGINE=.*?;/is';
                if (preg_match($pattern, $sql, $createMatch)) {
                    try {
                        $pdo->exec($createMatch[0]);
                        $created++;
                    } catch (PDOException $e) {
                        $errors[] = "`{$tableName}` → " . $e->getMessage();
                    }
                }
            }
            
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            
            $status = 'success';
            $message = "Migrate selesai! {$created} tabel baru dibuat, {$skipped} tabel sudah ada (dilewati).";
            if (count($errors) > 0) {
                $message .= "<br><br><b>Error:</b><br>";
                foreach ($errors as $err) {
                    $message .= "<code class='block text-xs mt-1 bg-red-50 p-1 rounded'>" . htmlspecialchars($err) . "</code>";
                }
            }
            
            // Refresh
            $stmt = $pdo->query("SHOW TABLES");
            $tableList = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            $status = 'error';
            $message = "Error saat migrate: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - InfinityFree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden border border-slate-100">

    <!-- Header -->
    <div class="bg-slate-900 px-6 py-5 text-white">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            Database Migration Tool
        </h1>
        <p class="text-slate-400 text-sm mt-1">Import dan kelola database dari file SQL project.</p>
    </div>

    <div class="p-6 space-y-6">

        <!-- Status Koneksi -->
        <div class="p-4 rounded-xl border <?php echo $dbConnected ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'; ?>">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full <?php echo $dbConnected ? 'bg-green-500' : 'bg-red-500'; ?>"></div>
                <div>
                    <h3 class="font-bold text-sm <?php echo $dbConnected ? 'text-green-800' : 'text-red-800'; ?>">
                        <?php echo $dbConnected ? 'Database Terhubung' : 'Koneksi Gagal'; ?>
                    </h3>
                    <p class="text-xs <?php echo $dbConnected ? 'text-green-600' : 'text-red-600'; ?> mt-0.5">
                        <?php if ($dbConnected): ?>
                            Host: <?php echo htmlspecialchars($dbHost); ?> | DB: <?php echo htmlspecialchars($dbName); ?> | Tabel: <?php echo count($tableList); ?>
                        <?php else: ?>
                            <?php echo htmlspecialchars($message); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <?php if ($action && $message && $status): ?>
            <div class="p-4 rounded-xl border <?php echo $status === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700'; ?>">
                <h3 class="font-bold text-sm mb-1"><?php echo $status === 'success' ? '✅ Berhasil!' : '❌ Error!'; ?></h3>
                <p class="text-sm"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($dbConnected): ?>
        <!-- Aksi Cards -->
        <div class="grid md:grid-cols-3 gap-4">
            
            <!-- Card 1: Migrate New -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 flex flex-col">
                <span class="inline-block bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-3 w-fit">AMAN</span>
                <h3 class="font-bold text-slate-800 text-sm">Migrate Tabel Baru</h3>
                <p class="text-xs text-slate-500 mt-2 flex-1">Hanya membuat tabel yang <b>belum ada</b>. Data yang sudah ada tidak akan dihapus.</p>
                <a href="?action=migrate_new" onclick="return confirm('Buat tabel-tabel baru yang belum ada di database?')"
                   class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Migrate
                </a>
            </div>
            
            <!-- Card 2: Full Import -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 flex flex-col">
                <span class="inline-block bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-3 w-fit">OVERWRITE</span>
                <h3 class="font-bold text-slate-800 text-sm">Import Full SQL</h3>
                <p class="text-xs text-slate-500 mt-2 flex-1">Eksekusi seluruh file SQL (DROP + CREATE + INSERT). <b>Data lama akan ditimpa!</b></p>
                <a href="?action=import_full" onclick="return confirm('PERINGATAN: Ini akan MENIMPA seluruh tabel dan data yang ada! Lanjutkan?')"
                   class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import
                </a>
            </div>
            
            <!-- Card 3: Drop All -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 flex flex-col">
                <span class="inline-block bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-3 w-fit">BAHAYA</span>
                <h3 class="font-bold text-slate-800 text-sm">Hapus Semua Tabel</h3>
                <p class="text-xs text-slate-500 mt-2 flex-1">Menghapus <b>seluruh tabel</b> di database. Gunakan jika ingin reset total.</p>
                <a href="?action=drop_all" onclick="return confirm('BAHAYA! Semua tabel dan data di database akan DIHAPUS PERMANEN! Yakin?')"
                   class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Drop All
                </a>
            </div>
        </div>

        <!-- Daftar Tabel -->
        <?php if (count($tableList) > 0): ?>
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                <h3 class="font-bold text-sm text-slate-700">Tabel di Database (<?php echo count($tableList); ?>)</h3>
            </div>
            <div class="p-4 flex flex-wrap gap-2">
                <?php foreach ($tableList as $t): ?>
                    <span class="bg-slate-100 text-slate-700 text-xs font-mono px-3 py-1.5 rounded-lg border border-slate-200"><?php echo htmlspecialchars($t); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Security Warning -->
        <div class="text-center pt-2">
            <p class="text-xs bg-yellow-50 text-yellow-700 py-2 px-4 rounded-lg inline-flex items-center gap-2 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Segera hapus file public/migrate.php setelah proses selesai!
            </p>
        </div>
    </div>
</div>
</body>
</html>
