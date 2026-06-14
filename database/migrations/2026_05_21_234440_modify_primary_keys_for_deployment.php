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
        $driver = Schema::getConnection()->getDriverName();

        // 1. Update 'users' table
        // We will add 'user_code' and make it PK. 
        // Note: For existing data, we need to populate it first.
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_code', 50)->nullable();
        });
        
        // Sync user_code with id for existing data (cross-database compatible)
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update(['user_code' => 'USR-' . $user->id]);
        }

        // Make user_code NOT NULL (MySQL only - SQLite doesn't support ALTER COLUMN)
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY user_code VARCHAR(50) NOT NULL");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('user_code');
        });

        // 2. Update 'items' table
        // 'code' is already unique. Keep as-is, no change needed for SQLite.
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE items MODIFY code VARCHAR(50) NOT NULL");
        }

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
            $table->dropUnique(['user_code']);
            $table->dropColumn('user_code');
        });
    }
};
