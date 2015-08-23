<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateEpisodeversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodevers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->unsigned();
            $table->foreign('episode_id')->references('id')->on('episodes');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('first');
            $table->string('title');
            $table->string('alias')->nullable();
            $table->date('release_date');
            $table->string('url')->nullable();
            $table->text('sc');
            $table->integer('duration');
            $table->string('poster_url')->nullable();
            $table->text('introduction')->nullable();
            $table->timestamps();
        });
        DB::insert('INSERT INTO episodevers(episode_id, user_id, first, title, alias, release_date, url, sc, duration, poster_url, introduction, created_at, updated_at) SELECT episodes.id, user_id, 1, title, alias, release_date, url, sc, duration, poster_url, introduction, episodes.created_at, episodes.updated_at FROM episodes JOIN histories ON (episodes.deleted_at IS NULL AND episodes.id = histories.model_id AND histories.model = 1 AND histories.type = 0)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('episodevers');
    }
}
