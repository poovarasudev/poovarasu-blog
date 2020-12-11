<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {

    return [
        'user_id' => 3,
        'title' => $faker->name,
        'description' => $faker->text(250),
        'email' => "abc3@gmail.com",
        'created_at' => $faker->dateTimeBetween($startDate = '- 0 years', $endDate = 'now'),
        'updated_at' => $faker->dateTimeBetween($startDate = '-0 years', $endDate = 'now'),
    ];
});
