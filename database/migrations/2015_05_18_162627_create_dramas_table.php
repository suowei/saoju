<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDramasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dramas', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
            $table->string('alias')->nullable();
            $table->string('genre')->nullable();
            $table->boolean('original');
            $table->integer('count');
            $table->tinyInteger('state');
            $table->string('sc');
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
		Schema::drop('dramas');
	}

}
