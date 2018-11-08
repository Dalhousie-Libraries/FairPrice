<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlternateJournalTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alternate_journal_titles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('journal_id')->unsigned();
            $table->text("e_issn")->nullable();
            $table->text("p_issn")->nullable();
            $table->text("journal_title");
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
        Schema::dropIfExists('alternate_journal_titles');
    }
}
