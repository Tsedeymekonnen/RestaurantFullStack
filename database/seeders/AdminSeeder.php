<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminpassword'),
            'role' => 'admin',
            'personal_area' => 'Main Office', // Optional: put actual values or leave null if nullable
            'floor' => '5st Floor',
            'department' => 'Management',
            'remember_token' => Str::random(10),
        ]);
    }
}
