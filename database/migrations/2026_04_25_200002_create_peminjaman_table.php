<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel peminjaman: menyimpan data peminjaman barang via QR scan.
     * Terhubung ke item (barang) dan scan_session (sesi QR).
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peminjam');
            $table->string('kelas');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('item_code');           // Duplikat kode barang untuk referensi cepat
            $table->string('session_token', 64);   // Token QR yang digunakan
            $table->timestamp('waktu_pinjam');
            $table->timestamp('waktu_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('session_token');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
