<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '0123456789',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin123')
            ],
            [
                'first_name' => 'Ruan',
                'last_name' => 'User',
                'phone' => '0123456789',
                'email' => 'ruan@nwk.co.za',
                'password' => Hash::make('Ruan123')
            ]
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }
}
