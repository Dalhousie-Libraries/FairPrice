<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('journal_id')->unsigned();
            $table->integer('report_year');
            $table->decimal('price', 10, 2);
            $table->text('currency');
            $table->decimal('cost_per_use',10,2)->nullable();
            $table->decimal('adjusted_cost_per_use',10,2)->nullable();
            $table->timestamps();

            $table->foreign('journal_id')->references('id')->on('journals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
