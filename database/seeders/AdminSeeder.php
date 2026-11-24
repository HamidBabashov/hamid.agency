<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Admin hesabını .env-dəki ADMIN_EMAIL / ADMIN_PASSWORD ilə yaradır (və ya yeniləyir).
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('agency.admin.email');
        $password = config('agency.admin.password');

        if (blank($email) || blank($password)) {
            throw new RuntimeException('ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env');
        }

        if (strlen($password) < 10) {
            throw new RuntimeException('ADMIN_PASSWORD must be at least 10 characters long.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('agency.admin.name') ?: 'Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
