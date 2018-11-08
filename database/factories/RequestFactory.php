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

$factory->define(App\Request::class, function (Faker $faker) {
    $year = $faker->randomElement(array('2014','2015','2016','2017'));
    return [
        'journal_id' => random_int(\DB::table('journals')->min('id'), \DB::table('journals')->max('id')),
        'reporting_period_start' => $faker->dateTimeBetween($year . '-01-01', $year . '-06-01'),
        'reporting_period_end' => $faker->dateTimeBetween($year . '-07-01', $year . '-12-31'),
        'requests' => $faker->numberBetween(1, 50),
        'request_type' => $faker->numberBetween(0,3)
    ];
});