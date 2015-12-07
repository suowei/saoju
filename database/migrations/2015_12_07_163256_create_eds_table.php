<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('drama_id')->unsigned();
            $table->foreign('drama_id')->references('id')->on('dramas');
            $table->integer('episode_id')->default(0);
            $table->integer('song_id')->unsigned();
            $table->foreign('song_id')->references('id')->on('songs');
            $table->integer('user_id')->unsigned();
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
        Schema::drop('eds');
    }
}
