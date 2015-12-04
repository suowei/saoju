<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fteps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ft_id')->unsigned();
            $table->foreign('ft_id')->references('id')->on('fts');
            $table->string('title');
            $table->date('release_date');
            $table->string('url')->nullable();
            $table->text('staff');
            $table->string('poster_url')->nullable();
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
        Schema::drop('fteps');
    }
}
