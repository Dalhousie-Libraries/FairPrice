<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriorityThresholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('priority_thresholds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id'); //A simple number which indicates that a threshold level is associated with other thresholds.
            $table->text('threshold'); //A descriptor for the threshold
            $table->integer('threshold_value');
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
        Schema::dropIfExists('priority_thresholds');
    }
}
