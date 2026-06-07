<?php
/**
 * Script untuk membuat ZIP dari project dan memecahnya menjadi beberapa part
 * (Maksimal 9MB per file) agar bisa diupload ke InfinityFree.
 */

ini_set('max_execution_time', 600); // Waktu eksekusi 10 menit
ini_set('memory_limit', '512M');

$zipFileName = 'project.zip';
$chunkSize = 9 * 1024 * 1024; // 9MB (InfinityFree memiliki limit upload 10MB)

echo "Memulai proses kompresi...\n";

$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Gagal membuat file ZIP\n");
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__),
    RecursiveIteratorIterator::LEAVES_ONLY
);

// Folder dan file yang TIDAK perlu diikutkan
$excludeList = ['.git', 'node_modules', 'buat_zip.php', '.env']; 
// Catatan: Folder `vendor` tidak di-exclude karena di InfinityFree tidak bisa menjalankan `composer install`

foreach ($iterator as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen(__DIR__) + 1);
        $relativePath = str_replace('\\', '/', $relativePath); // standarisasi separator
        
        $skip = false;
        
        // Lewati file ZIP itu sendiri dan file part-nya
        if (strpos($relativePath, 'project.zip') === 0) {
            $skip = true;
        }

        foreach ($excludeList as $exclude) {
            if (strpos($relativePath, $exclude . '/') === 0 || $relativePath === $exclude) {
                $skip = true;
                break;
            }
        }

        if (!$skip) {
            $zip->addFile($filePath, $relativePath);
        }
    }
}

echo "Sedang menyimpan ZIP (Ini mungkin memakan waktu beberapa menit)...\n";
$zip->close();
echo "ZIP berhasil dibuat: $zipFileName\n";

echo "Mulai memecah file ZIP menjadi bagian-bagian kecil (Maks 9MB)...\n";
$handle = fopen($zipFileName, 'rb');
$part = 1;
while (!feof($handle)) {
    $buffer = fread($handle, $chunkSize);
    if ($buffer === false || strlen($buffer) === 0) break;
    
    $partName = sprintf("%s.%03d", $zipFileName, $part);
    file_put_contents($partName, $buffer);
    echo "-> Dibuat part: $partName (" . number_format(strlen($buffer) / 1024 / 1024, 2) . " MB)\n";
    $part++;
}
fclose($handle);

echo "\nSELESAI!\n";
echo "Jumlah total part: " . ($part - 1) . "\n";
echo "Silakan upload file project.zip.001, project.zip.002, dst. dan file unzip.php ke folder 'htdocs' di InfinityFree.\n";
echo "(Penting: Jangan upload project.zip yang utuh karena akan ditolak server InfinityFree)\n";
