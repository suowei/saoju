<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scvers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sc_id')->unsigned();
            $table->foreign('sc_id')->references('id')->on('scs');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('name');
            $table->string('alias')->nullable();
            $table->integer('club_id')->unsigned();
            $table->string('jobs')->nullable();
            $table->text('information')->nullable();
            $table->timestamps();
        });
        DB::insert('INSERT INTO scvers(sc_id, user_id, first, name, alias, club_id, jobs, information, created_at, updated_at) SELECT id, user_id, 1, name, alias, club_id, jobs, information, created_at, updated_at FROM scs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scvers');
    }
}
