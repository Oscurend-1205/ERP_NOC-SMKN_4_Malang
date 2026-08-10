<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perawatans', function (Blueprint $table) {
            $table->string('token_link')->nullable()->unique()->after('catatan');
            $table->string('teknisi_nama')->nullable()->after('token_link');
            $table->integer('biaya')->nullable()->after('teknisi_nama');
            $table->string('foto_bukti')->nullable()->after('biaya');
        });

        // Modifying ENUM column using raw statement because Laravel might complain without dbal
        DB::statement("ALTER TABLE perawatans MODIFY COLUMN status ENUM('menunggu', 'proses', 'menunggu_pengecekan', 'selesai') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perawatans', function (Blueprint $table) {
            $table->dropColumn(['token_link', 'teknisi_nama', 'biaya', 'foto_bukti']);
        });

        DB::statement("ALTER TABLE perawatans MODIFY COLUMN status ENUM('menunggu', 'proses', 'selesai') DEFAULT 'menunggu'");
    }
};
