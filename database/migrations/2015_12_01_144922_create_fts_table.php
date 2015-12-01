<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('host')->nullable();
            $table->string('poster_url')->nullable();
            $table->text('introduction')->nullable();
            $table->integer('reviews')->default(0);
            $table->integer('favorties')->default(0);
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
        Schema::drop('fts');
    }
}
