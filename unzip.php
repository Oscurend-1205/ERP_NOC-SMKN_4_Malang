<?php
/**
 * Script sederhana untuk mengekstrak file ZIP di hosting InfinityFree
 * yang membatasi fitur unzip bawaan dari File Manager.
 */

// Aktifkan display error untuk mempermudah debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$zipFile = 'project.zip'; // Nama file zip yang diupload

if (!file_exists($zipFile)) {
    die("Error: File <b>{$zipFile}</b> tidak ditemukan di server. Pastikan Anda sudah menguploadnya langsung ke folder htdocs.");
}

$zip = new ZipArchive;
$res = $zip->open($zipFile);

if ($res === TRUE) {
    echo "Sedang mengekstrak file <b>{$zipFile}</b>... Silakan tunggu.<br>";
    
    // Ekstrak ke folder tempat script ini dijalankan (htdocs)
    $zip->extractTo(__DIR__);
    $zip->close();
    
    echo "<h2 style='color: green;'>Sukses! Proyek berhasil diekstrak!</h2>";
    echo "Silakan hapus file <b>unzip.php</b> dan <b>{$zipFile}</b> dari hosting demi keamanan.";
} else {
    echo "<h2 style='color: red;'>Gagal mengekstrak!</h2>";
    switch ($res) {
        case ZipArchive::ER_EXISTS:
            echo "File sudah ada.";
            break;
        case ZipArchive::ER_INCONS:
            echo "Zip file tidak konsisten/rusak.";
            break;
        case ZipArchive::ER_INVAL:
            echo "Argumen tidak valid.";
            break;
        case ZipArchive::ER_MEMORY:
            echo "Memori tidak cukup.";
            break;
        case ZipArchive::ER_NOENT:
            echo "File tidak ditemukan.";
            break;
        case ZipArchive::ER_NOZIP:
            echo "Bukan file ZIP yang valid.";
            break;
        case ZipArchive::ER_OPEN:
            echo "Tidak dapat membuka file.";
            break;
        case ZipArchive::ER_READ:
            echo "Error saat membaca.";
            break;
        case ZipArchive::ER_SEEK:
            echo "Error pencarian posisi file.";
            break;
        default:
            echo "Kode error tidak diketahui: " . $res;
    }
}
