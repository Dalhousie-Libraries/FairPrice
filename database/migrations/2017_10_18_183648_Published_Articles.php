<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublishedArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('published_articles', function (Blueprint $table) {
            $table->increments('id'); #Unique Download Report ID

            $table->integer('journal_id')->unsigned(); #Journal ID
            $table->integer('report_year'); #The earliest date this report tracks
            $table->integer('published_articles'); #How many Dal Articles were published in this period

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
