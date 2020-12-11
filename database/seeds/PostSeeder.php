<?php

use App\Image;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($month = 1; $month <= 120; $month++) {
            $postCount = random_int(15, 75);
            for ($i = 0; $i < $postCount; $i++) {
                $post = \App\Post::create([
                    'user_id' => random_int(1, 6),
                    'email' => $faker->email,
                    'title' => $faker->name,
                    'description' => $faker->text(240),
                    'created_at' => now()->subMonths($month)->addDays(random_int(5, 15))->subDays(random_int(5, 15)),
                ]);

                $iteration = random_int(0, 4);
                for ($j = 0; $j < $iteration; $j++) {
                    \App\PostTag::create([
                        'post_id' => $post->id,
                        'tag_id' => random_int(1, 7),
                    ]);
                }

                $iteration = random_int(0, 4);
                for ($k = 1; $k <= $iteration; $k++) {
                    Image::create([
                        'post_id' => $post->id,
                        'full_url' => 'https://loremflickr.com/640/480/paris?random=' . $k,
                    ]);
                }
            }

            $userCount = random_int(1, 3);
            for ($i = 0; $i < $userCount; $i++) {
                \App\User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->email,
                    'email_verified_at' => now(),
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'created_at' => now()->subMonths($month)->addDays(random_int(5, 15))->subDays(random_int(5, 15)),
                ]);
            }
        }

        for ($month = 1; $month <= 24; $month++) {
            $postCount = random_int(3, 15);
            for ($i = 0; $i < $postCount; $i++) {
                $faker = Factory::create();
                $post = \App\Post::create([
                    'user_id' => random_int(1, 6),
                    'email' => $faker->email,
                    'title' => $faker->name,
                    'description' => $faker->text(240),
                    'created_at' => now()->addMonths($month)->addDays(random_int(5, 15))->subDays(random_int(5, 15)),
                ]);

                $iteration = random_int(0, 4);
                for ($j = 0; $j < $iteration; $j++) {
                    \App\PostTag::create([
                        'post_id' => $post->id,
                        'tag_id' => random_int(1, 7),
                    ]);
                }

                $iteration = random_int(0, 4);
                for ($k = 1; $k <= $iteration; $k++) {
                    Image::create([
                        'post_id' => $post->id,
                        'full_url' => 'https://loremflickr.com/640/480/paris?random=' . $k,
                    ]);
                }
            }

            $userCount = random_int(1, 3);
            for ($i = 0; $i < $userCount; $i++) {
                \App\User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->email,
                    'email_verified_at' => now(),
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'created_at' => now()->addMonths($month)->addDays(random_int(5, 15))->subDays(random_int(5, 15)),
                ]);
            }
        }
    }
}
