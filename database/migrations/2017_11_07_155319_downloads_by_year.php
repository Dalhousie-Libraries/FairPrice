<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DownloadsByYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads_by_year', function (Blueprint $table) {
            $table->increments('id'); #Platform ID
            $table->integer('journal_id')->unsigned(); #FK Journal ID
            $table->integer('platform_id')->unsigned(); #FK Journal ID
            $table->integer('report_year');
            $table->integer('published_year');
            $table->integer('downloads_reported');

            $table->foreign('journal_id')
                    ->references('id')->on('journals');
            $table->foreign('platform_id')
                    ->references('id')->on('platforms');
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
