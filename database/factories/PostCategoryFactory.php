<?php

use Faker\Generator as Faker;

$factory->define(App\PostCategory::class, function (Faker $faker) {
    return [
        'title' => $faker->colorName, //use color names as categories names
    ];
});
