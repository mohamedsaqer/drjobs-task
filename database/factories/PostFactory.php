<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'user_id'       =>  rand(1, 100),
        'title'         => $faker->jobTitle,
        'content'       => $faker->text(500),
        'post_category' => rand(1, 5),
        'status'        => $faker->randomElement(['draft','publish','private']),
    ];
});
