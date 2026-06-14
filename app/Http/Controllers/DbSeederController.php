<?php

namespace App\Http\Controllers;

use App\Models\User;

class DbSeederController extends Controller
{
    /**
     * Seed default user accounts (Admin & Superadmin).
     * Safe to call multiple — uses updateOrCreate.
     * NOTE: User model has 'password' => 'hashed' cast,
     * so we pass plain text — Eloquent handles hashing.
     */
    public function resetAndSeed()
    {
        $results = [];

        // Superadmin
        User::updateOrCreate(
            ['email' => 'superadmin@noc.smkn4malang.sch.id'],
            [
                'user_code' => 'USR-001',
                'name' => 'Super Admin NOC',
                'username' => 'superadmin',
                'password' => 'Superadmin2026',
                'role' => 'Superadmin',
                'is_active' => true,
            ]
        );
        $results[] = 'Superadmin account created/updated.';

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@noc.smkn4malang.sch.id'],
            [
                'user_code' => 'USR-002',
                'name' => 'Admin NOC',
                'username' => 'admin',
                'password' => 'Admin2026',
                'role' => 'Admin',
                'is_active' => true,
            ]
        );
        $results[] = 'Admin account created/updated.';

        return response()->json([
            'status' => 'success',
            'message' => 'Database seeding completed.',
            'details' => $results,
        ]);
    }
}
