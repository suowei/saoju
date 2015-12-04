<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtepversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftepvers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ftep_id')->unsigned();
            $table->foreign('ftep_id')->references('id')->on('fteps');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('title');
            $table->date('release_date');
            $table->string('url')->nullable();
            $table->text('staff');
            $table->string('poster_url')->nullable();
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
        Schema::drop('ftepvers');
    }
}
