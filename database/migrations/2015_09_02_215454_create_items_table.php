<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('list_id')->unsigned();
            $table->foreign('list_id')->references('id')->on('lists');
            $table->integer('no');
            $table->integer('drama_id')->unsigned();
            $table->foreign('drama_id')->references('id')->on('dramas');
            $table->integer('episode_id')->unsigned()->default(0);
            $table->text('review')->nullable();
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
        Schema::drop('items');
    }
}
