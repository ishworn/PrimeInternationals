<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'access dashboard',
            'manage customers',
            'manage shipments',
            'access recycle bin',
            'manage users',
            'manage all payments', // Includes all payment-related sections
            'track shipments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
