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
        // Superadmin account (user_code is set later by modify_primary_keys migration)
        $superadmin = DB::table('users')->where('email', 'superadmin@noc.smkn4malang.sch.id')->first();

        if (!$superadmin) {
            DB::table('users')->insert([
                'name' => 'Super Admin NOC',
                'username' => 'superadmin',
                'email' => 'superadmin@noc.smkn4malang.sch.id',
                'password' => Hash::make('Superadmin2026'),
                'role' => 'Superadmin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Admin account (user_code is set later by modify_primary_keys migration)
        $admin = DB::table('users')->where('email', 'admin@noc.smkn4malang.sch.id')->first();

        if (!$admin) {
            DB::table('users')->insert([
                'name' => 'Admin NOC',
                'username' => 'admin',
                'email' => 'admin@noc.smkn4malang.sch.id',
                'password' => Hash::make('Admin2026'),
                'role' => 'Admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('email', 'superadmin@noc.smkn4malang.sch.id')
            ->delete();

        DB::table('users')
            ->where('email', 'admin@noc.smkn4malang.sch.id')
            ->delete();
    }
};
