<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('drama_id')->unsigned()->default(0);
            $table->integer('episode_id')->unsigned()->default(0);
            $table->string('title');
            $table->string('alias')->nullable();
            $table->string('artist');
            $table->string('url')->nullable();
            $table->string('poster_url')->nullable();
            $table->text('staff');
            $table->text('lyrics')->nullable();
            $table->integer('reviews')->default(0);
            $table->integer('favorites')->default(0);
            $table->softDeletes();
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
        Schema::drop('songs');
    }
}
