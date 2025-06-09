<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the super-admin role if it doesn't exist
        Role::firstOrCreate(['name' => 'super-admin']);

        // Create super admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin',
            'password' => bcrypt('superadmin'),
        ]);

        // Assign super-admin role to the user
        $user->assignRole('super-admin');

        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        $vendor = User::create([
            'name' => 'Vendor',
            'email' => 'vendor@gmail.com',
            'username' => 'vendor',
            'password' => bcrypt('vendor'),
        ]);
        $vendor->assignRole($vendorRole);

        // Create permission
$manageCustomersPermission = Permission::firstOrCreate(['name' => 'manage customers']);



// Assign permission to vendor role
$vendorRole->givePermissionTo($manageCustomersPermission);
    }
    
}
