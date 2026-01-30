<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('type', 'admin')->delete();
        User::insert([
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@yopmail.com',
                'password' => Hash::make('Admin1!@'),
                'type' => 'admin',
                'status' => 'active',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
