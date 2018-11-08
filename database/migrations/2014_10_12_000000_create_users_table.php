<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('id');
            $table->string('email');
            $table->string('username');
            $table->string('name');
            $table->boolean('is_student')->default(false);
            $table->boolean('is_faculty')->default(false);
            $table->boolean('is_staff')->default(false);
            $table->string('faculty_id')->nullable();
            $table->string('department_id')->nullable();
            $table->string('password');
            $table->string('comments')->default("");
            $table->integer('role')->default(0); #0 for none, 1 for admin, 2 for superadmin
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
