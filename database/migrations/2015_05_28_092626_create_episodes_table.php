<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('episodes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('drama_id')->unsigned();
            $table->foreign('drama_id')->references('id')->on('dramas');
            $table->string('title');
            $table->string('alias')->nullable();
            $table->date('release_date');
            $table->string('url')->nullable();
            $table->text('sc');
            $table->integer('duration');
            $table->string('poster_url')->nullable();
            $table->text('introduction')->nullable();
            $table->integer('reviews')->default(0);
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
		Schema::drop('episodes');
	}

}
