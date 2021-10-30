<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate([
            'name' => 'role-read',
            'display_name' => 'Role',
        ]);
        Permission::firstOrCreate([
            'name' => 'role-create',
            'display_name' => 'Role',
        ]);
        Permission::firstOrCreate([
            'name' => 'role-update',
            'display_name' => 'Role',
        ]);
        Permission::firstOrCreate([
            'name' => 'role-delete',
            'display_name' => 'Role',
        ]);

        Permission::firstOrCreate([
            'name' => 'user-read',
            'display_name' => 'User',
        ]);
        Permission::firstOrCreate([
            'name' => 'user-create',
            'display_name' => 'User',
        ]);
        Permission::firstOrCreate([
            'name' => 'user-update',
            'display_name' => 'User',
        ]);
        Permission::firstOrCreate([
            'name' => 'user-delete',
            'display_name' => 'User',
        ]);

        Permission::firstOrCreate([
            'name' => 'company-read',
            'display_name' => 'Company',
        ]);
        Permission::firstOrCreate([
            'name' => 'company-create',
            'display_name' => 'Company',
        ]);
        Permission::firstOrCreate([
            'name' => 'company-update',
            'display_name' => 'Company',
        ]);
        Permission::firstOrCreate([
            'name' => 'company-delete',
            'display_name' => 'Company',
        ]);

        Permission::firstOrCreate([
            'name' => 'currencies-read',
            'display_name' => 'Currencies',
        ]);
        Permission::firstOrCreate([
            'name' => 'currencies-create',
            'display_name' => 'Currencies',
        ]);
        Permission::firstOrCreate([
            'name' => 'currencies-update',
            'display_name' => 'Currencies',
        ]);
        Permission::firstOrCreate([
            'name' => 'currencies-delete',
            'display_name' => 'Currencies',
        ]);

        Permission::firstOrCreate([
            'name' => 'tax-rate-read',
            'display_name' => 'Tax Rate',
        ]);
        Permission::firstOrCreate([
            'name' => 'tax-rate-create',
            'display_name' => 'Tax Rate',
        ]);
        Permission::firstOrCreate([
            'name' => 'tax-rate-update',
            'display_name' => 'Tax Rate',
        ]);
        Permission::firstOrCreate([
            'name' => 'tax-rate-delete',
            'display_name' => 'Tax Rate',
        ]);

        Permission::firstOrCreate([
            'name' => 'profile-read',
            'display_name' => 'Profile',
        ]);
        Permission::firstOrCreate([
            'name' => 'profile-update',
            'display_name' => 'Profile',
        ]);
    }
}
