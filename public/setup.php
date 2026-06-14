<?php
/**
 * Setup Script untuk InfinityFree / Shared Hosting
 * HANYA melakukan import database + bersihkan cache.
 * TIDAK mengubah file .env — kelola .env secara manual via File Manager.
 */

$baseDir = dirname(__DIR__);
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ============================================================
    // 1. Clear Laravel cache (karena InfinityFree memblokir shell_exec)
    // ============================================================
    $cacheFiles = [
        $baseDir . '/bootstrap/cache/config.php',
        $baseDir . '/bootstrap/cache/routes-v7.php',
        $baseDir . '/bootstrap/cache/packages.php',
        $baseDir . '/bootstrap/cache/services.php',
    ];
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
    $message .= "Cache sistem berhasil dibersihkan.\n";

    // ============================================================
    // 2. Import SQL dump (buat tabel + seed akun login)
    // ============================================================
    $sqlFile = $baseDir . '/database/erp_noc_smkn4malang.sql';
    if (file_exists($sqlFile)) {
        // Baca kredensial DB dari .env yang sudah ada
        $envPath = $baseDir . '/.env';
        if (!file_exists($envPath)) {
            $errors[] = "File .env tidak ditemukan! Buat file .env terlebih dahulu di File Manager.";
        } else {
            $envContent = file_get_contents($envPath);
            $dbHost = $dbName = $dbUser = $dbPass = '';

            if (preg_match('/^DB_HOST=(.*)$/m', $envContent, $m)) $dbHost = trim($m[1]);
            if (preg_match('/^DB_DATABASE=(.*)$/m', $envContent, $m)) $dbName = trim($m[1]);
            if (preg_match('/^DB_USERNAME=(.*)$/m', $envContent, $m)) $dbUser = trim($m[1]);
            if (preg_match('/^DB_PASSWORD=(.*)$/m', $envContent, $m)) $dbPass = trim($m[1]);

            if (empty($dbHost) || empty($dbName)) {
                $errors[] = "DB_HOST atau DB_DATABASE belum diisi di .env. Lengkapi dulu via File Manager.";
            } else {
                try {
                    $pdo = new PDO("mysql:host=$dbHost;port=3306", $dbUser, $dbPass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 30,
                    ]);

                    $sql = file_get_contents($sqlFile);
                    // Hapus baris CREATE DATABASE & USE (hosting sudah punya DB sendiri)
                    $sql = preg_replace('/^\s*CREATE DATABASE.*$/mi', '', $sql);
                    $sql = preg_replace('/^\s*USE\s+`?\w+`?\s*;.*$/mi', '', $sql);

                    $pdo->exec($sql);
                    $message .= "Database berhasil diimport (semua tabel + akun login sudah dibuat).\n";

                } catch (PDOException $e) {
                    $errors[] = "Import SQL gagal: " . $e->getMessage();
                }
            }
        }
    } else {
        $errors[] = "File database/erp_noc_smkn4malang.sql tidak ditemukan.";
    }

    // ============================================================
    // 3. Pastikan folder storage ada
    // ============================================================
    $storageDirs = [
        $baseDir . '/storage/framework/sessions',
        $baseDir . '/storage/framework/cache',
        $baseDir . '/storage/framework/views',
        $baseDir . '/storage/logs',
    ];
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup Database (InfinityFree)</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 560px; }
        h2 { margin-top: 0; color: #1f2937; }
        button { background: #3b82f6; color: white; border: none; padding: 12px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%; font-size: 15px; }
        button:hover { background: #2563eb; }
        .alert { background: #d1fae5; color: #065f46; padding: 15px; border-radius: 5px; margin-bottom: 15px; white-space: pre-line; }
        .error { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 5px; margin-bottom: 15px; white-space: pre-line; }
        .info { background: #eff6ff; color: #1e40af; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; line-height: 1.6; }
        .btn-link { display: block; text-align: center; background: #10b981; color: white; text-decoration: none; padding: 12px; border-radius: 5px; font-weight: bold; margin-top: 10px; }
        .btn-link:hover { background: #059669; }
        .warning { color: #ef4444; font-size: 12px; text-align: center; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Setup Database & Aplikasi</h2>

        <?php if ($message || $errors): ?>
            <?php if ($message): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php foreach ($errors as $err): ?>
                <div class="error"><?php echo htmlspecialchars($err); ?></div>
            <?php endforeach; ?>

            <?php if (empty($errors)): ?>
                <a href="/" class="btn-link">Buka Aplikasi</a>
                <a href="/reset-database" class="btn-link" style="background:#6366f1; margin-top:8px;">Re-Seed Akun Login Saja</a>
            <?php endif; ?>

            <p class="warning">PENTING: Hapus file public/setup.php ini dari File Manager setelah selesai!</p>

        <?php else: ?>
            <div class="info">
                Script ini akan:<br>
                1. <b>Import database</b> dari <code>database/erp_noc_smkn4malang.sql</code> (membuat semua tabel + akun login).<br>
                2. <b>Bersihkan cache</b> Laravel.<br>
                3. <b>Tidak mengubah .env</b> — kelola .env secara manual di File Manager.
            </div>

            <form method="POST">
                <button type="submit" onclick="return confirm('Import database sekarang? Tabel yang sudah ada akan di-replace.')">
                    Import Database & Bersihkan Cache
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
