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
            'name' => 'Demo',
            'email' => 'demo@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'test 1',
            'email' => 'test1@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'test 2',
            'email' => 'test2@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\User::create([
            'name' => 'test 3',
            'email' => 'test3@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $month = 10;
        $overAllIterations = random_int(20, 35);
        for ($i = 0; $i < $overAllIterations; $i++) {
            $faker = Factory::create();
            $user = \App\User::first();
            $post = \App\Post::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'title' => $faker->title,
                'description' => $faker->text(240),
                'created_at' => now()->subYears($month),
            ]);
            $month--;
        }
    }
}
