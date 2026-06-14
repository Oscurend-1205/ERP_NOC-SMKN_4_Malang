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
        // Add missing foreign key columns to items table
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable()->after('location_id');
            }
            if (!Schema::hasColumn('items', 'asal_barang_id')) {
                $table->unsignedBigInteger('asal_barang_id')->nullable()->after('supplier_id');
            }
            if (!Schema::hasColumn('items', 'kondisi_barang_id')) {
                $table->unsignedBigInteger('kondisi_barang_id')->nullable()->after('asal_barang_id');
            }
        });

        // Add foreign key constraints (only if tables exist)
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasTable('suppliers') && !Schema::hasColumn('items', 'fk_items_supplier_id')) {
                $table->foreign('supplier_id', 'fk_items_supplier_id')
                    ->references('id')->on('suppliers')->onDelete('set null');
            }
            if (Schema::hasTable('asal_barangs') && !Schema::hasColumn('items', 'fk_items_asal_barang_id')) {
                $table->foreign('asal_barang_id', 'fk_items_asal_barang_id')
                    ->references('id')->on('asal_barangs')->onDelete('set null');
            }
            if (Schema::hasTable('kondisi_barangs') && !Schema::hasColumn('items', 'fk_items_kondisi_barang_id')) {
                $table->foreign('kondisi_barang_id', 'fk_items_kondisi_barang_id')
                    ->references('id')->on('kondisi_barangs')->onDelete('set null');
            }
        });

        // Add kondisi_saat_kembali to peminjaman table
        if (Schema::hasTable('peminjaman') && !Schema::hasColumn('peminjaman', 'kondisi_saat_kembali')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->string('kondisi_saat_kembali', 50)->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('fk_items_supplier_id');
            $table->dropForeign('fk_items_asal_barang_id');
            $table->dropForeign('fk_items_kondisi_barang_id');
            $table->dropColumn(['supplier_id', 'asal_barang_id', 'kondisi_barang_id']);
        });

        if (Schema::hasTable('peminjaman') && Schema::hasColumn('peminjaman', 'kondisi_saat_kembali')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->dropColumn('kondisi_saat_kembali');
            });
        }
    }
};
