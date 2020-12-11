<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
                'created_at' => now()->subMonths($month),
            ]);

            $iterations = random_int(3, 15);
            for ($j = 0; $j < $iterations; $j++) {
                \App\PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => random_int(1, 7),
                ]);
            }
            $month--;
        }
    }
}
