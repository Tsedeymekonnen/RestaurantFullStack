<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'admin@admin.com', // Admin email
            'password' => Hash::make('adminpassword'), // Hashed password
            'role' => 'admin', // Role for admin
        ]);
    }
}