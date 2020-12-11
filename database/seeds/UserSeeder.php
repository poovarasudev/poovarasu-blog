<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Abc',
            'email' => 'abc@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Demo User',
            'email' => 'demo@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Test 1',
            'email' => 'test1@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Test 2',
            'email' => 'test2@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Test 3',
            'email' => 'test3@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Test 4',
            'email' => 'test4@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Test 5',
            'email' => 'test5@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'Test 6',
            'email' => 'test6@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
