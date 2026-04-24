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
        // Buat user admin NOC
        User::factory()->create([
            'name' => 'Admin NOC',
            'email' => 'admin@noc.smkn4malang.sch.id',
            'password' => bcrypt('admin123'),
        ]);

        // Seed data ERP
        $this->call(ERPSeeder::class);
    }
}
