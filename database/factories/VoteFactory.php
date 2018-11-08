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



$factory->define(App\Vote::class, function (Faker $faker) {
    $faculty = random_int(\DB::table('faculties')->min('faculty_id'), \DB::table('faculties')->max('faculty_id'));

    
    return [
        'election_id' => random_int(\DB::table('elections')->min('id'), \DB::table('elections')->max('id')),
        'journal_id' => random_int(\DB::table('journals')->min('id'), \DB::table('journals')->max('id')),
        'vote' => random_int(0,2),
        'faculty' => $faculty,
        'department' => 0,
        'type' => $faker->numberBetween(0,3),
        'comments' => $faker->sentences(5, true)
    ];
});

