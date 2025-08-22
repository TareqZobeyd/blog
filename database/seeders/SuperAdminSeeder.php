<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $superAdmin = User::where('email', 'admin@example.com')->first();

        if (!$superAdmin) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::SUPER_ADMIN,
                'email_verified_at' => now(),
            ]);

            // Super Admin user created successfully
        } else {
            // Update existing user to super admin
            $superAdmin->update([
                'role' => UserRole::SUPER_ADMIN,
            ]);

            // Existing user updated to Super Admin role
        }
    }
}
