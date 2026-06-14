<?php
/**
 * fix-strict-mode.php
 * ============================================================
 * Fix MySQL ONLY_FULL_GROUP_BY + Session + Cache untuk InfinityFree
 * ============================================================
 *
 * Error yang diatasi:
 *   SQLSTATE[42000]: Syntax error or access violation: 1055
 *   'database.items.code' isn't in GROUP BY
 *
 * Cara pakai:
 *   1. Upload file ini ke folder htdocs/ di InfinityFree
 *   2. Buka: https://yourdomain.com/fix-strict-mode.php
 *   3. Klik tombol "Jalankan Perbaikan"
 *   4. HAPUS file ini setelah selesai!
 */

$baseDir = dirname(__DIR__);
$message = '';
$errors = [];
$steps = [];

// ============================================
// Parse .env
// ============================================
function parseEnv($path) {
    $env = [];
    if (!file_exists($path)) return $env;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }
        $env[$key] = $value;
    }
    return $env;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ============================================
    // STEP 1: Bersihkan Cache Laravel
    // ============================================
    $cacheFiles = [
        $baseDir . '/bootstrap/cache/config.php',
        $baseDir . '/bootstrap/cache/routes-v7.php',
        $baseDir . '/bootstrap/cache/packages.php',
        $baseDir . '/bootstrap/cache/services.php',
    ];
    $cacheCleared = 0;
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            @unlink($file);
            $cacheCleared++;
        }
    }
    // Hapus compiled views
    $viewCache = $baseDir . '/storage/framework/views';
    if (is_dir($viewCache)) {
        foreach (glob($viewCache . '/*.php') as $viewFile) {
            @unlink($viewFile);
            $cacheCleared++;
        }
    }
    $steps[] = ['✅', 'Cache Laravel dibersihkan (' . $cacheCleared . ' file)'];

    // ============================================
    // STEP 2: Pastikan folder storage ada
    // ============================================
    $storageDirs = [
        $baseDir . '/storage/framework/sessions',
        $baseDir . '/storage/framework/cache',
        $baseDir . '/storage/framework/views',
        $baseDir . '/storage/logs',
        $baseDir . '/storage/app/public',
    ];
    $dirsCreated = 0;
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
            $dirsCreated++;
        }
    }
    $steps[] = ['✅', 'Folder storage dipastikan ada (' . $dirsCreated . ' dibuat)'];

    // ============================================
    // STEP 3: Fix MySQL ONLY_FULL_GROUP_BY
    // ============================================
    $envPath = $baseDir . '/.env';
    if (!file_exists($envPath)) {
        $steps[] = ['❌', 'File .env tidak ditemukan! Buat .env terlebih dahulu.'];
    } else {
        $env = parseEnv($envPath);
        $dbHost = $env['DB_HOST'] ?? 'localhost';
        $dbPort = $env['DB_PORT'] ?? '3306';
        $dbName = $env['DB_DATABASE'] ?? '';
        $dbUser = $env['DB_USERNAME'] ?? '';
        $dbPass = $env['DB_PASSWORD'] ?? '';

        if (empty($dbName)) {
            $steps[] = ['❌', 'DB_DATABASE belum diisi di .env'];
        } else {
            try {
                $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 15,
                ]);

                // Cek sql_mode saat ini
                $stmt = $pdo->query("SELECT @@SESSION.sql_mode AS mode");
                $currentMode = $stmt->fetchColumn();

                if (stripos($currentMode, 'ONLY_FULL_GROUP_BY') !== false) {
                    // Hapus ONLY_FULL_GROUP_BY
                    $modes = array_filter(
                        array_map('trim', explode(',', $currentMode)),
                        fn($m) => strtoupper($m) !== 'ONLY_FULL_GROUP_BY'
                    );
                    $newMode = implode(',', $modes);

                    // Set GLOBAL (butuh SUPER privilege — mungkin gagal di shared hosting)
                    try {
                        $pdo->exec("SET GLOBAL sql_mode = '$newMode'");
                        $steps[] = ['✅', "MySQL GLOBAL sql_mode berhasil diubah"];
                    } catch (PDOException $e) {
                        $steps[] = ['⚠️', "GLOBAL sql_mode tidak bisa diubah (butuh SUPER privilege). Mencoba SESSION..."];
                    }

                    // Set SESSION (selalu berhasil)
                    $pdo->exec("SET SESSION sql_mode = '$newMode'");
                    $steps[] = ['✅', "MySQL SESSION sql_mode berhasil diubah"];
                } else {
                    $steps[] = ['✅', 'ONLY_FULL_GROUP_BY sudah tidak aktif di MySQL'];
                }

                // ============================================
                // STEP 4: Verifikasi tabel penting ada
                // ============================================
                $requiredTables = ['users', 'categories', 'locations', 'items', 'item_movements', 'peminjaman', 'scan_sessions'];
                $missingTables = [];
                foreach ($requiredTables as $table) {
                    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                    if ($stmt->rowCount() === 0) {
                        $missingTables[] = $table;
                    }
                }
                if (!empty($missingTables)) {
                    $steps[] = ['⚠️', 'Tabel belum ada: ' . implode(', ', $missingTables) . ' — jalankan setup.php atau /run-migrations'];
                } else {
                    $steps[] = ['✅', 'Semua tabel penting sudah ada (' . count($requiredTables) . ' tabel)'];
                }

                // ============================================
                // STEP 5: Verifikasi kolom penting
                // ============================================
                $columnChecks = [
                    'items' => ['sub_prefix', 'supplier_id', 'asal_barang_id', 'kondisi_barang_id'],
                    'peminjaman' => ['kondisi_saat_kembali', 'keterangan_kembali', 'foto_kembali'],
                ];
                $missingCols = [];
                foreach ($columnChecks as $table => $columns) {
                    foreach ($columns as $col) {
                        $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$col'");
                        if ($stmt->rowCount() === 0) {
                            $missingCols[] = "$table.$col";
                        }
                    }
                }
                if (!empty($missingCols)) {
                    $steps[] = ['⚠️', 'Kolom belum ada: ' . implode(', ', $missingCols) . ' — jalankan /run-migrations'];
                } else {
                    $steps[] = ['✅', 'Semua kolom terbaru sudah ada'];
                }

                // ============================================
                // STEP 6: Verifikasi akun login
                // ============================================
                $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('Admin','Superadmin') AND is_active = 1");
                $adminCount = $stmt->fetchColumn();
                if ($adminCount > 0) {
                    $steps[] = ['✅', "Akun admin aktif ditemukan ($adminCount akun)"];
                } else {
                    $steps[] = ['⚠️', 'Tidak ada akun admin aktif! Jalankan /reset-database atau fix-login123456789.php'];
                }

            } catch (PDOException $e) {
                $steps[] = ['❌', 'Koneksi database gagal: ' . $e->getMessage()];
            }
        }
    }

    // ============================================
    // STEP 7: Verifikasi APP_KEY
    // ============================================
    if (file_exists($envPath)) {
        $env = parseEnv($envPath);
        if (empty($env['APP_KEY']) || $env['APP_KEY'] === 'base64:') {
            $steps[] = ['❌', 'APP_KEY belum diisi! Login tidak akan bekerja tanpa APP_KEY yang valid.'];
        } else {
            $steps[] = ['✅', 'APP_KEY sudah ter-set'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix ERP NOC - InfinityFree</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 620px; }
        h1 { font-size: 22px; color: #1a1a1a; margin-bottom: 4px; }
        .subtitle { color: #666; font-size: 13px; margin-bottom: 24px; }
        .step { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px; line-height: 1.5; }
        .step:last-child { border-bottom: none; }
        .step-icon { flex-shrink: 0; font-size: 16px; margin-top: 1px; }
        .step-text { color: #374151; }
        .btn { display: block; width: 100%; padding: 14px; background: #3F51B5; color: #fff; border: none; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s; text-align: center; }
        .btn:hover { background: #303F9F; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .warning { background: #fff3cd; color: #856404; padding: 12px 16px; border-radius: 10px; margin-top: 16px; font-size: 12px; line-height: 1.6; border: 1px solid #ffc107; }
        .success-box { background: #d1fae5; color: #065f46; padding: 16px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; text-align: center; border: 1px solid #6ee7b7; }
        .info-box { background: #eff6ff; color: #1e40af; padding: 14px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; line-height: 1.7; border: 1px solid #bfdbfe; }
        .link-row { display: flex; gap: 8px; margin-top: 12px; }
        .link-row a { flex: 1; display: block; text-align: center; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; transition: opacity 0.2s; }
        .link-row a:hover { opacity: 0.85; }
        .link-primary { background: #3F51B5; color: #fff; }
        .link-secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    </style>
</head>
<body>
<div class="card">
    <h1>🔧 Fix ERP NOC</h1>
    <p class="subtitle">Perbaikan MySQL Strict Mode + Sesi + Cache untuk InfinityFree</p>

    <?php if (!empty($steps)): ?>
        <div style="margin-bottom: 16px;">
            <?php foreach ($steps as [$icon, $text]): ?>
                <div class="step">
                    <span class="step-icon"><?= $icon ?></span>
                    <span class="step-text"><?= htmlspecialchars($text) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
        $hasError = false;
        foreach ($steps as [$icon, $_]) {
            if ($icon === '❌') { $hasError = true; break; }
        }
        ?>

        <?php if (!$hasError): ?>
            <div class="success-box">
                🎉 <strong>Perbaikan selesai!</strong> Silakan coba login kembali.
            </div>
        <?php endif; ?>

        <div class="link-row">
            <a href="/" class="link-primary">Buka Aplikasi</a>
            <a href="/run-migrations" class="link-secondary">Jalankan Migrasi</a>
            <a href="/fix-login123456789.php" class="link-secondary">Reset Password</a>
        </div>

        <div class="warning">
            ⚠️ <strong>PENTING:</strong> Hapus file <code>fix-strict-mode.php</code> dan <code>fix-login123456789.php</code> dari server setelah selesai untuk keamanan!
        </div>

    <?php else: ?>
        <div class="info-box">
            Script ini akan melakukan perbaikan otomatis:<br>
            <strong>1.</strong> Bersihkan cache Laravel (config, route, view)<br>
            <strong>2.</strong> Pastikan folder storage ada<br>
            <strong>3.</strong> Nonaktifkan <code>ONLY_FULL_GROUP_BY</code> di MySQL<br>
            <strong>4.</strong> Verifikasi tabel & kolom database lengkap<br>
            <strong>5.</strong> Verifikasi akun admin aktif<br>
            <strong>6.</strong> Verifikasi APP_KEY sudah ter-set<br><br>
            Klik tombol di bawah untuk menjalankan semua perbaikan sekaligus.
        </div>

        <form method="POST">
            <button type="submit" class="btn" onclick="return confirm('Jalankan semua perbaikan sekarang?')">
                🚀 Jalankan Perbaikan
            </button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
