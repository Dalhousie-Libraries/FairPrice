<?php

use Illuminate\Database\Seeder;
use Faker as Faker;

class RelationshipSeeder extends Seeder
{
    /**
     * Test Data Seeder
     *
     /@return void
     */
    public function run()
    {
        
        $num_journals = 100;
        $num_platforms = 40;
        $num_platform_links = 40000;
        $num_prices = 4000;
        $num_requests = 4000;
        $num_historicalchoices = 40000;
        $num_elections = 5;
        $num_votes = 50000;

        echo("Generating $num_journals random Journals\n");
        factory(App\Journal::class, $num_journals)->create();
        echo("Generating $num_platforms random Platforms\n");
        factory(App\Platform::class, $num_platforms)->create();
        echo("Generating $num_prices random Prices\n");
        factory(App\Price::class, $num_prices)->create();
        echo("Generating $num_historicalchoices random Historical Choices\n");
        factory(App\HistoricalChoice::class, $num_historicalchoices)->create();
        echo("Generating $num_elections random elections\n");
        factory(App\Election::class, $num_elections)->create();
        echo("Generating $num_votes random votes\n");
        factory(App\Vote::class, $num_votes)->create();

        $min_journal = \DB::table('journals')->min('id');
        $max_journal = \DB::table('journals')->max('id');

        $faker = Faker\Factory::create();
        for($i=$min_journal;$i<=$max_journal;$i++) {
            $journal = $i;
            $random_number = random_int(3, 10);
            for($j=0;$j < $random_number; $j++) {
                $platform = random_int(\DB::table('platforms')->min('id'), \DB::table('platforms')->max('id'));
                
                $perpetual_access = $faker->boolean();
                $priority_package = $faker->boolean();
                $is_embargo = $faker->boolean();
                $embargo_checked = $faker->dateTimeThisYear();
                if($is_embargo) {
                    $embargo_length = random_int(0, 12) . " months";
                    $embargo_updated = $faker->dateTimeThisYear();
                    
                } else {
                    $embargo_length = "";
                    $embargo_updated = $faker->dateTimeThisYear();
                }
                $start_date = random_int(2010,2015);
                $end_date = random_int(2016,2018);
                $start_volume = $faker->numerify('####');
                $end_volume = $faker->numerify('####');

                App\Journal::find($journal)->platforms()->attach($platform, [
                    'perpetual_access' => $perpetual_access,
                    'priority_package' => $priority_package,
                    'aggregator_platform' => false,
                    'is_embargo' => $is_embargo,
                    'embargo_length' => $embargo_length,
                    'embargo_updated' => $embargo_updated,
                    'date_embargo_checked' => $embargo_checked,
                    'years' => $start_date . "-" . $end_date,
                    'start_volume' => $start_volume,
                    'end_volume' => $end_volume
                ]);
            }
        }


        
        
    }
}
