<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Downloads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id'); #Unique Download Report ID

            $table->integer('journal_id')->unsigned(); #Journal ID
            $table->integer('report_year');
            $table->integer('downloads_reported'); #How many downloads occured in this period
            $table->timestamps();

            $table->foreign('journal_id')
            ->references('id')->on('journals');

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
