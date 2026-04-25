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
        User::updateOrCreate(
            ['email' => 'superadmin@noc.smkn4malang.sch.id'],
            [
                'name' => 'Super Admin NOC',
                'username' => 'superadmin',
                'password' => bcrypt('password'),
                'role' => 'Superadmin',
                'is_active' => true,
            ]
        );

        // Buat user Admin NOC
        User::updateOrCreate(
            ['email' => 'admin@noc.smkn4malang.sch.id'],
            [
                'name' => 'Admin NOC',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'is_active' => true,
            ]
        );

        // Seed data ERP
        $this->call(ERPSeeder::class);
    }
}
