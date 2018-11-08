<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlatformJournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('platform_journal', function (Blueprint $table) {
            $table->increments('id'); #Relationship ID
            $table->integer('journal_id')->unsigned(); #FK Journal ID
            $table->integer('platform_id')->unsigned(); #FK Platform ID
            $table->boolean('perpetual_access')->nullable();
            $table->text('perpetual_access_coverage')->nullable();
            $table->boolean('priority_package')->default(false);
            $table->boolean('aggregator_platform')->default(false);
            $table->text('years')->nullable();
            $table->text('start_volume')->nullable();
            $table->text('end_volume')->nullable();
            $table->boolean('is_embargo')->nullable();
            $table->text('embargo_length')->nullable();
            $table->text('embargo_updated')->nullable();
            $table->date('date_embargo_checked')->nullable();
            $table->foreign('journal_id')
                ->references('id')->on('journals');
            $table->foreign('platform_id')
                ->references('id')->on('platforms');
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
