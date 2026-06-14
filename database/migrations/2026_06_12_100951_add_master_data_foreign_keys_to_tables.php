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
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            }
            if (!Schema::hasColumn('items', 'asal_barang_id')) {
                $table->foreignId('asal_barang_id')->nullable()->constrained('asal_barangs')->nullOnDelete();
            }
            if (!Schema::hasColumn('items', 'kondisi_barang_id')) {
                $table->foreignId('kondisi_barang_id')->nullable()->constrained('kondisi_barangs')->nullOnDelete();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'jurusan_id')) {
                $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            }
            if (Schema::hasColumn('items', 'asal_barang_id')) {
                $table->dropForeign(['asal_barang_id']);
                $table->dropColumn('asal_barang_id');
            }
            if (Schema::hasColumn('items', 'kondisi_barang_id')) {
                $table->dropForeign(['kondisi_barang_id']);
                $table->dropColumn('kondisi_barang_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'jurusan_id')) {
                $table->dropForeign(['jurusan_id']);
                $table->dropColumn('jurusan_id');
            }
        });
    }
};
