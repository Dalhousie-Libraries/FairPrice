<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Votes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id'); #Unique Vote ID
            $table->integer('election_id')->unsigned();
            $table->integer('journal_id')->unsigned();
            $table->integer('vote');
            $table->integer('faculty');
            $table->integer('department')->nullable();
            $table->integer('type'); #0 = Other, 1 = Student, 2 = Faculty, 3 = Staff
            $table->text('comments')->nullable();
           
            $table->foreign('election_id')
                ->references('id')->on('elections');
            $table->foreign('journal_id')
                ->references('id')->on('journals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
