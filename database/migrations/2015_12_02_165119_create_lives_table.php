<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->dateTime('showtime');
            $table->text('information')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('record_url')->nullable();
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
        Schema::drop('lives');
    }
}
