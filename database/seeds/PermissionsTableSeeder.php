<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['id' => 1, 'name' => 'create-post', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'edit-post', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'delete-post', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'view post', 'guard_name' => 'web'],
            ['id' => 5, 'name' => 'create-comment', 'guard_name' => 'web'],
            ['id' => 6, 'name' => 'edit-comment', 'guard_name' => 'web'],
            ['id' => 7, 'name' => 'delete-comment', 'guard_name' => 'web'],
            ['id' => 8, 'name' => 'view-comment', 'guard_name' => 'web'],
            ['id' => 9, 'name' => 'create-tag', 'guard_name' => 'web'],
            ['id' => 10, 'name' => 'edit-tag', 'guard_name' => 'web'],
            ['id' => 11, 'name' => 'delete-tag', 'guard_name' => 'web'],
            ['id' => 12, 'name' => 'view-tag', 'guard_name' => 'web'],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
