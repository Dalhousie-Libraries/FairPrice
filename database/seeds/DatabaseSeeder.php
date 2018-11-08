<?php

use Illuminate\Database\Seeder;
//use Excel;
use App\Faculty;
use App\Department;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $num_elections = 5;
        
        $subjects = fopen("subject_list.csv", "r") or die("Unable to open file!");
        fgets($subjects);
        while(!feof($subjects)) {
            $s = new App\Subject;
            $s->subject = trim(fgets($subjects));
            echo $s->subject . "\n";
            $s->save();
          }
        
        
        Excel::load('faculty_department_list.xlsx', function($reader) {
        // Loop through all sheets
            $reader->each(function($sheet) {
                // Loop through all rows
                $sheet->each(function($row) {
                    $f = Faculty::firstOrCreate(['id' => $row->faculty_id,
                        'faculty_aliases' => $row->faculty_code,
                        'faculty_name' => $row->faculty_name]);
                    

                    $d = Department::firstOrCreate(['id' => $row->department_bit,
                    'faculty_id' => $row->faculty_id,
                    'department_aliases' => $row->department_code,
                    'department_name' => $row->department_name]);
                    });
            });
        });

        echo("Seeding database with existing platforms\n");
        $p = new App\Platform;
        $p->name = "Cambridge";
        $p->is_primary=true;
        $p->save();

        $p = new App\Platform;
        $p->name = "Oxford";
        $p->is_primary=true;
        $p->save();

        $p = new App\Platform;
        $p->name = "Sage";
        $p->is_primary=true;
        $p->save();

        $p = new App\Platform;
        $p->name = "Springer";
        $p->is_primary=true;
        $p->save();

        $p = new App\Platform;
        $p->name = "Taylor & Francis";
        $p->is_primary=true;
        $p->save();

        $p = new App\Platform;
        $p->name = "Wiley";
        $p->is_primary=true;
        $p->save();

        //#7
        $p = new App\Platform;
        $p->name = "ABI Inform Global";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Academic Search Premier";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "ACM Digital Library";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Agricultural and Environmental Science";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Biological Science";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Business Source Complete";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Canadian Business and Current Affairs";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "CINAHL";
        $p->is_primary=false;
        $p->save();
        //#15
        $p = new App\Platform;
        $p->name = "Dentistry and Oral Sciences Source";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Environment Complete";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Factiva";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Film and Television Literature Index";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Free or OA";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "GeoScienceWorld";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "HeinOnline";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Highwire Press Journals";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "International Bibliography of Theatre and Dance";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "IOP";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "IOS Press Journals";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "JSTOR";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "LEXIS/NEXIS Academic";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Library Literature and Information Science";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Ovid";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Periodical Archives Online";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Project Muse";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "PubMed Central";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Research Library";
        $p->is_primary=false;
        $p->save();

        $p = new App\Platform;
        $p->name = "Scielo";
        $p->is_primary=false;
        $p->save();

  //      echo("Loading existing journal data");
  //      Artisan::call('command:import');

        //echo("Generating $num_elections random elections\n");
        //factory(App\Election::class, $num_elections)->create();



        $l = new App\Library;
        $l->bit_value = 1;
        $l->library_name = "Killam";
        $l->save(); 

        $l = new App\Library;
        $l->bit_value = 2;
        $l->library_name = "Kellogg";
        $l->save();

        $l = new App\Library;
        $l->bit_value = 4;
        $l->library_name = "Dunn";
        $l->save();

        $l = new App\Library;
        $l->bit_value = 8;
        $l->library_name = "Sexton";
        $l->save();

        $l = new App\Library;
        $l->bit_value = 16;
        $l->library_name = "MacRae";
        $l->save();

        $l = new App\Library;
        $l->bit_value = 32;
        $l->library_name = "King's";
        $l->save();
        

        //echo("Populating database relationships Journal <- -> Platform");
        //Seeder::call('RelationshipSeeder');
        
    }
}
