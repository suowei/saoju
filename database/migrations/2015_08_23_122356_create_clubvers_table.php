<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubvers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('name');
            $table->text('information')->nullable();
            $table->timestamps();
        });
        DB::insert('INSERT INTO clubvers(club_id, user_id, first, name, information, created_at, updated_at) SELECT id, user_id, 1, name, information, created_at, updated_at FROM clubs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clubvers');
    }
}
