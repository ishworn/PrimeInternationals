<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
    }
}
