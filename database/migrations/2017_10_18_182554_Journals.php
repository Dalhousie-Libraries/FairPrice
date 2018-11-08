<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Journals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id'); #The Unique Journal ID, our PK

            $table->text('e_issn')->nullable(); #The EISSN Number
            $table->text('p_issn')->nullable(); #The PISSN number
            
            $table->text('jup')->nullable(); #The JUP
            $table->text('doi')->nullable(); #The DOI
            
            $table->text('journal_title'); #The Journal Title
            $table->text('abbreviation')->nullable(); #Abbreviation for the journak
            $table->text('proprietary_identifier')->nullable(); #Acronym for the Journal
            $table->text('url')->nullable(); #Journal URL
            
            $table->text('subject_1')->nullable(); #Journal URL
            $table->text('subject_2')->nullable(); #Journal URL
            $table->text('subject_3')->nullable(); #Journal URL
            $table->text('subject_4')->nullable(); #Journal URL
            $table->text('user_subject')->nullable(); #Choose Your Subject

            $table->text('fund')->nullable(); #Fund
            $table->text('domain')->nullable(); #Domain
            $table->text('journal_status')->nullable(); #Domain
        
            $table->integer('retained_by')->nullable(); #bit flag (1 = Killam, 2 = Kellog, etc)
            $table->integer('libraries_holding_print')->nullable(); #bit flag (1 = Killam, 2 = Kellog, etc)
            $table->integer('threshold_levels')->nullable(); #bit flag (1 = Threshold A, 2 = Threshold B, 4 = Threshold C, etc)
            $table->text('comments')->nullable(); #Comments
           
            $table->integer('is_priority')->default(false); #Is this a priority 0=no, 1=yes, 2=maybe
            $table->boolean('is_subscribed')->default(true); #Boolean (True = Yes)
            $table->boolean("is_recommendation")->default(false); #Recommendation (True = Retain)
            $table->boolean("is_consultation")->default(false);  #Consultation (True = in Progress)
            $table->boolean("is_print_access")->default(false);  #Print Access (True=Yes)
            $table->text("print_holdings")->nullable();
            
            $table->timestamps(); #Created date, Last edit (update) date
            $table->softDeletes(); #Nothing is ever deleted from the table, only marked as 'disabled'
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
