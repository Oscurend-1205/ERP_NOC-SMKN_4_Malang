<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan akun Superadmin dan memperbarui role admin yang sudah ada.
     */
    public function up(): void
    {
        // Cek apakah superadmin sudah ada
        $existing = DB::table('users')->where('email', 'superadmin@noc.smkn4malang.sch.id')->first();

        if (!$existing) {
            DB::table('users')->insert([
                'name' => 'Super Admin NOC',
                'username' => 'superadmin',
                'email' => 'superadmin@noc.smkn4malang.sch.id',
                'password' => Hash::make('SuperAdmin@2026'),
                'role' => 'Superadmin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update role admin yang sudah ada menjadi 'Admin' (pastikan konsisten)
        DB::table('users')
            ->where('email', 'admin@noc.smkn4malang.sch.id')
            ->update(['role' => 'Admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('email', 'superadmin@noc.smkn4malang.sch.id')
            ->delete();
    }
};
