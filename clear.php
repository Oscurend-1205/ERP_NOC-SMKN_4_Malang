<?php
/**
 * Script Pembersih Cache Kritis untuk InfinityFree / Shared Hosting
 * Akses melalui: https://domain-anda.com/clear.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = __DIR__;
$files = [
    $baseDir . '/bootstrap/cache/config.php',
    $baseDir . '/bootstrap/cache/packages.php',
    $baseDir . '/bootstrap/cache/services.php',
];

foreach (glob($baseDir . '/bootstrap/cache/routes-v7*.php') as $f) {
    $files[] = $f;
}
foreach (glob($baseDir . '/storage/framework/views/*.php') as $f) {
    $files[] = $f;
}

$deleted = 0;
foreach ($files as $file) {
    if (file_exists($file)) {
        @unlink($file);
        $deleted++;
    }
}

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Clear Cache - ERP NOC</title>
    <style>
        body { font-family: system-ui, sans-serif; padding: 40px; background: #f8fafc; color: #1e293b; }
        .card { background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 15px; }
    </style>
</head>
<body>
    <div class='card'>
        <h2 style='color:#16a34a; margin-top:0;'>✅ Pembersihan Cache Berhasil!</h2>
        <p>Sebanyak <b>{$deleted}</b> file cache lokal (config/services/packages/routes) telah dibersihkan dari server hosting.</p>
        <a class='btn' href='/deploy-setup'>Lanjutkan ke Setup Deployment →</a>
    </div>
</body>
</html>";
