<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@example.com';
        $password = env('ADMIN_PASSWORD', 'password123');

        $admin = User::firstOrNew(['email' => $email]);

        $admin->forceFill([
            'name' => 'Administrator',
            'password' => Hash::make($password),
            'role' => 'admin',
        ])->save();
    }
}
