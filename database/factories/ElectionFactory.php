<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Election::class, function (Faker $faker) {

    return [
        'created_at' => $faker->dateTimeThisYear(),
        'updated_at' => $faker->dateTimeThisYear(),
        'start_date' => $faker->dateTimeThisYear(),
        'end_date' => null,
        'election_name' => $faker->words(2, true),
        'election_description' => $faker->sentences(5, true),
    ];
});
