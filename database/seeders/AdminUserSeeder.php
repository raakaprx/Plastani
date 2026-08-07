<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::where('email', 'admin@plastani.com')->delete();

        User::create([
            'name' => 'Administrator PLASTANI',
            'email' => 'admin@plastani.com',
            'password' => Hash::make('admin123'),
            'admin_plastani' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin created: admin@plastani.com / admin123');
    }
}
