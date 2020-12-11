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
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        DB::table('roles')->insert([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        DB::table('roles')->insert([
            'name' => 'accountant',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::all();
        $role = Role::find(1);
        $role->syncPermissions($permissions);

        $user = User::find(1);
        $user->assignRole('admin');
    }
}
