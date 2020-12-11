<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use App\PostTag;
use App\Tag;
use Faker\Generator as Faker;

$factory->define(PostTag::class, function (Faker $faker) {
    return [
        'post_id' => $faker->unique()->numberBetween(1, 105),
        'tag_id' => $faker->numberBetween(4, 10),
    ];
});
