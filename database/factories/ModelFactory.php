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

    $publish_interval =
        $faker->dateTimeBetween(
            $startDate = '-5 years',
            $endDate = 'now',
            $timezone = date_default_timezone_get()
        )->format('Y-m-d h:m:s')
        . '|'
        . $faker->dateTimeBetween(
            $startDate = 'now',
            $endDate = '+1 years',
            $timezone = date_default_timezone_get()
        )->format('Y-m-d h:m:s');

    $bidding_interval =
        $faker->dateTimeBetween(
            $startDate = '-5 years',
            $endDate = 'now',
            $timezone = date_default_timezone_get()
        )->format('Y-m-d h:m:s')
        . '|'
        . $faker->dateTimeBetween(
            $startDate = 'now',
            $endDate = '+1 years',
            $timezone = date_default_timezone_get()
        )->format('Y-m-d h:m:s');

    return [
        'name' => $faker->words($nb = 4, $asText = true),
        'desc' => $faker->paragraph,
        'price' => $faker->numberBetween($min = 50, $max = 1000),
        'public' => $faker->numberBetween($min = 0, $max = 1),
        'publish_interval' => $publish_interval,
        'bidding_interval' => $bidding_interval
    ];
});
