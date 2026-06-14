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
        if (Schema::hasTable('peminjaman')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                if (!Schema::hasColumn('peminjaman', 'keterangan_kembali')) {
                    $table->text('keterangan_kembali')->nullable()->after('kondisi_saat_kembali');
                }
                if (!Schema::hasColumn('peminjaman', 'foto_kembali')) {
                    $table->string('foto_kembali', 255)->nullable()->after('keterangan_kembali');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('peminjaman')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->dropColumn(['keterangan_kembali', 'foto_kembali']);
            });
        }
    }
};
