<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $allPermissions = [
            'access dashboard',
            'manage customers',
            'manage shipments',
            'access recycle bin',
            'manage users',
            'manage all payments',
            'track shipments',
        ];

        // Assign permissions to roles
        $roles = [
            'super-admin' => $allPermissions,

            'vendor' => [
                'access dashboard',
                'manage customers',
              
                

            ],

            'receptionist' => [
                'access dashboard',
                'manage customers',
                'manage shipments',
                'access recycle bin',
                'manage all payments',
                'track shipments',
                
            ],

            'accountant' => [
                'manage all payments',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
