<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmins',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super-admin',
            ],
            [
                'name' => 'Receptionist User',
                'username' => 'receptionist',
                'email' => 'reception@example.com',
                'password' => Hash::make('password'),
                'role' => 'receptionist',
            ],
            [
                'name' => 'Accountant User',
                'username' => 'accountant',
                'email' => 'accountant@example.com',
                'password' => Hash::make('password'),
                'role' => 'accountant',
            ],
            [
                'name' => 'Vendor User',
                'username' => 'vendor',
                'email' => 'vendor@example.com',
                'password' => Hash::make('password'),
                'role' => 'vendor',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate([
                'email' => $data['email'],
                'username' => $data['username'],
            ], [
                'name' => $data['name'],
                'password' => $data['password'],

            ]);

            $user->assignRole($data['role']);
        }
    }
}
