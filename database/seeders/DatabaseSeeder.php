<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user Superadmin
        User::factory()->create([
            'name' => 'Super Admin NOC',
            'username' => 'superadmin',
            'email' => 'superadmin@noc.smkn4malang.sch.id',
            'password' => bcrypt('SuperAdmin@2026'),
            'role' => 'Superadmin',
            'is_active' => true,
        ]);

        // Buat user Admin NOC
        User::factory()->create([
            'name' => 'Admin NOC',
            'username' => 'admin',
            'email' => 'admin@noc.smkn4malang.sch.id',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'is_active' => true,
        ]);

        // Seed data ERP
        $this->call(ERPSeeder::class);
    }
}
