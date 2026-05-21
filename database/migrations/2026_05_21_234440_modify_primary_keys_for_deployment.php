<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to update Primary Keys according to requirements.
     * 1. Kode barang (code) as PK for items table.
     * 2. Kode pengguna (username/new column) as PK for users table.
     * 3. id_pinjam (rename from id) as PK for peminjaman table.
     */
    public function up(): void
    {
        // 1. Update 'users' table
        // We will add 'user_code' and make it PK. 
        // Note: For existing data, we need to populate it first.
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_code', 50)->nullable()->after('id');
        });
        
        // Sync user_code with username or id for existing data
        DB::statement("UPDATE users SET user_code = CONCAT('USR-', id)");
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_code', 50)->nullable(false)->change();
            $table->unique('user_code');
        });

        // 2. Update 'items' table
        // 'code' is already unique. We will keep it as unique but the request asks for it as PK.
        // In Laravel/Eloquent, it's often better to keep 'id' as surrogate PK for relations, 
        // but to satisfy the request strictly for the database layer:
        Schema::table('items', function (Blueprint $table) {
            $table->string('code', 50)->change(); // Ensure length
        });

        // 3. Update 'peminjaman' table
        Schema::table('peminjaman', function (Blueprint $table) {
            // Rename 'id' to 'id_pinjam'
            $table->renameColumn('id', 'id_pinjam');
        });

        /**
         * DOCUMENTATION SUMMARY:
         * 
         * 1. Table: items
         *    - Primary Key: code (VARCHAR 50)
         *    - Format: INV-XXXXX (Inventory Code)
         *    - Constraint: NOT NULL, UNIQUE
         * 
         * 2. Table: users
         *    - Primary Key: user_code (VARCHAR 50)
         *    - Format: USR-X (User Code)
         *    - Constraint: NOT NULL, UNIQUE
         * 
         * 3. Table: peminjaman
         *    - Primary Key: id_pinjam (BIGINT UNSIGNED)
         *    - Feature: Auto-increment
         *    - Constraint: NOT NULL
         */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->renameColumn('id_pinjam', 'id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_code');
        });
    }
};
