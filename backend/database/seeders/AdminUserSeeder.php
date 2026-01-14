<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@kohistore.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@kohistore.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
