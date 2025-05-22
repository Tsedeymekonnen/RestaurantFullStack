<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'email' => 'superadmin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superadminpassword'),
            'role' => 'super_admin',
            'personal_area' => 'Headquarters',  // or whatever you want
            'floor' => '1st Floor',
            'department' => 'Executive',
            'remember_token' => Str::random(10),
        ]);
    }
}
