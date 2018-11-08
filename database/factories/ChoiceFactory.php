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

$factory->define(App\HistoricalChoice::class, function (Faker $faker) {
    $year = $faker->randomElement(array(2014, 2015, 2016, 2017));
    return [
        'journal_id' => random_int(\DB::table('journals')->min('id'), \DB::table('journals')->max('id')),
        'subscription_year' => random_int(2014, 2017)
    ];
});
