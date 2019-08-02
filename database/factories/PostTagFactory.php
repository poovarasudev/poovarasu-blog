<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use App\PostTag;
use App\Tag;
use Faker\Generator as Faker;

$factory->define(PostTag::class, function (Faker $faker) {
    return [
        'post_id' => $faker->numberBetween(81, 90),
        'tag_id' => $faker->numberBetween(2, 10),
    ];
});
