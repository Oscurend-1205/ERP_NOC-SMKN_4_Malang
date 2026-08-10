<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_movements', function (Blueprint $table) {
            $table->string('jenis_barang_masuk')->nullable()->after('type');
            $table->date('rentang_waktu_peminjaman')->nullable()->after('jenis_barang_masuk');
            $table->decimal('biaya_peminjaman', 15, 2)->nullable()->after('rentang_waktu_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_movements', function (Blueprint $table) {
            $table->dropColumn(['jenis_barang_masuk', 'rentang_waktu_peminjaman', 'biaya_peminjaman']);
        });
    }
};

