<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Admin',
            'guard_name' => 'web',
            'description' => 'Admin Description'
        ]);

        DB::table('roles')->insert([
            'name' => 'Customer',
            'guard_name' => 'web',
            'description' => 'Customer Description'
        ]);

        DB::table('roles')->insert([
            'name' => 'Accountant',
            'guard_name' => 'web',
            'description' => 'Accountant Description'
        ]);

        DB::table('roles')->insert([
            'name' => 'Blogger',
            'guard_name' => 'web',
            'description' => 'Blogger Description'
        ]);

        $permissions = Permission::all();
        $role = Role::find(1);
        $role->syncPermissions($permissions);

        $user = User::find(1);
        $user->assignRole('Admin');

        $user = User::find(2);
        $user->assignRole('Admin');
    }
}
