<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateInitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'phone' => '01712340889',
            'address' => 'Natore',
            'status' => '1',

        ]);

        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);
    }
}
