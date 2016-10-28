<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->words($nb = 4, $asText = true),
        'desc' => $faker->paragraph,
        'public' => $faker->numberBetween($min = 0, $max = 1),
        'publish_interval' => ($faker->date($format = 'Y-m-d h:m:s', $max = 'now') . "|" . $faker->date($format = 'Y-m-d h:m:s', $min = 'now')),
        'bidding_interval' => ($faker->date($format = 'Y-m-d h:m:s', $max = 'now') . "|" . $faker->date($format = 'Y-m-d h:m:s', $min = 'now'))
    ];
});
