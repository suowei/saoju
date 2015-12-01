<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftvers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ft_id')->unsigned();
            $table->foreign('ft_id')->references('id')->on('fts');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('title');
            $table->string('host')->nullable();
            $table->string('poster_url')->nullable();
            $table->text('introduction')->nullable();
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
        Schema::drop('ftvers');
    }
}
