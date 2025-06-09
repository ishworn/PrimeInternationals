<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;


class TrackingPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'track shipments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
