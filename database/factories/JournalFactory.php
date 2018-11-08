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

$factory->define(App\Journal::class, function (Faker $faker) {

    $faculty = random_int(\DB::table('faculties')->min('faculty_id'), \DB::table('faculties')->max('faculty_id'));
    $departments = random_int(App\Faculty::where('faculty_id', $faculty)->min('department_bit'),App\Faculty::where('faculty_id', $faculty)->sum('department_bit'));
    return [
        'e_issn' => $faker->numerify('####-####'),
        'p_issn' => $faker->numerify('####-####'),
        'jup' => $faker->numerify('######'),
        'doi' =>$faker->bothify('##.####/????##'),
        'journal_title' => $faker->sentence(5, true),
        'abbreviation' => $faker->sentence(2, true),
        'acronym' => $faker->lexify('????'),
        'url' => $faker->url(),
        'is_subscribed' => $faker->boolean(),
        'fund' => $faker->words(2, true),
        'domain' => $faker->words(2, true),
        'faculty' => $faculty,
        'departments' => $departments,
        'journal_status' => $faker->randomElement(['Ceasing', 'Transfering', null]),
        'is_print_access' => $faker->randomElement([true, false, null]),
        'priority' => $faker->numberBetween(0,2),
        'is_recommendation' => $faker->boolean(),
        'is_consultation' => $faker->boolean(),
        'retained_by' => $faker->numberBetween(0,31),
        'print_holdings' => $faker->numberBetween(0,31),
        'comments' => $faker->sentences(5, true),
        'subject_1' => App\Subject::find(random_int(\DB::table('subjects')->min('id'), \DB::table('subjects')->max('id')))->subject,
    ];
});
