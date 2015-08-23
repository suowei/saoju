<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateDramaversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dramavers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('drama_id')->unsigned();
            $table->foreign('drama_id')->references('id')->on('dramas');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('title');
            $table->string('alias')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('era')->default(0);
            $table->string('genre')->nullable();
            $table->boolean('original');
            $table->integer('count');
            $table->tinyInteger('state');
            $table->string('sc');
            $table->string('poster_url')->nullable();
            $table->text('introduction')->nullable();
            $table->timestamps();
        });
        DB::insert('INSERT INTO dramavers(drama_id, user_id, first, title, alias, type, era, genre, original, count, state, sc, poster_url, introduction, created_at, updated_at) SELECT dramas.id, user_id, 1, title, alias, dramas.type, era, genre, original, count, state, sc, poster_url, introduction, dramas.created_at, dramas.updated_at FROM dramas JOIN histories ON (dramas.deleted_at IS NULL AND dramas.id = histories.model_id AND histories.model = 0 AND histories.type = 0)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dramavers');
    }
}
