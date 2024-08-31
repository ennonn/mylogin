<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create the admin user with verified email
        $admin = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password', // Plain text password; the model's mutator will hash it
            'role' => 'admin',
            'email_verified_at' => now(), // Mark the email as verified
        ]);

        // Assign the admin role
        $admin->assignRole('admin');
    }
}
