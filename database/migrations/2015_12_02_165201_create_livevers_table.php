<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livevers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('live_id')->unsigned();
            $table->foreign('live_id')->references('id')->on('lives');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('title');
            $table->dateTime('showtime');
            $table->text('information')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('record_url')->nullable();
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
        Schema::drop('livevers');
    }
}
