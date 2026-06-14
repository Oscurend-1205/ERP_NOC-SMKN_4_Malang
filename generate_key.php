<?php
/**
 * Script untuk generate APP_KEY dan memperbaiki MissingAppKeyException
 * Jalankan script ini di browser!
 */

// Pastikan file .env ada
$envPath = __DIR__ . '/.env';
$envExamplePath = __DIR__ . '/.env.example';

if (!file_exists($envPath)) {
    if (!file_exists($envExamplePath)) {
        die("Error: File .env.example tidak ditemukan!");
    }
    copy($envExamplePath, $envPath);
    echo "✅ File .env berhasil dibuat dari .env.example<br>";
}

// Load .env
$envContent = file_get_contents($envPath);

// Generate APP_KEY
function generateRandomKey($length = 32)
{
    return 'base64:' . base64_encode(random_bytes($length));
}

$newKey = generateRandomKey();

// Ganti APP_KEY di .env
if (strpos($envContent, 'APP_KEY=') !== false) {
    $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $newKey, $envContent);
} else {
    $envContent = "APP_KEY=" . $newKey . "\n" . $envContent;
}

// Simpan kembali ke .env
if (file_put_contents($envPath, $envContent)) {
    echo "✅ APP_KEY berhasil di-generate dan disimpan ke .env<br>";
} else {
    die("❌ Error: Tidak bisa menyimpan ke file .env!");
}

// Clear cache
echo "🔄 Membersihkan cache...<br>";

// Coba clear cache via shell_exec
if (function_exists('shell_exec')) {
    shell_exec('php artisan config:clear 2>&1');
    shell_exec('php artisan cache:clear 2>&1');
    shell_exec('php artisan route:clear 2>&1');
    shell_exec('php artisan view:clear 2>&1');
    echo "✅ Cache berhasil dibersihkan!<br>";
} else {
    echo "⚠️ Tidak bisa membersihkan cache via shell, silakan jalankan manual via console.php<br>";
}

echo "<br>🎉 Berhasil! Sekarang coba buka aplikasi Anda kembali!";
echo "<br><br><a href='console.php' style='display: inline-block; padding: 10px 20px; background: #3490dc; color: white; text-decoration: none; border-radius: 5px;'>Buka Console</a>";
?>
