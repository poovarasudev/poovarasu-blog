<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'tag_name' => $faker->text(5),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
