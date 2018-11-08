<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_choices', function (Blueprint $table) {
            $table->increments('id'); #Unique Download Report ID

            $table->integer('journal_id')->unsigned(); #Journal ID
            $table->integer('subscription_year'); #The earliest date this report tracks
           
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
        Schema::dropIfExists('historical_choices');
    }
}
