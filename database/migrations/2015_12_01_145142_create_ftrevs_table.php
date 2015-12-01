<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtrevsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ftrevs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ft_id')->unsigned();
            $table->foreign('ft_id')->references('id')->on('fts');
            $table->integer('ftep_id')->unsigned()->default(0);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title')->nullable();
            $table->text('content');
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
        Schema::drop('ftrevs');
    }
}
