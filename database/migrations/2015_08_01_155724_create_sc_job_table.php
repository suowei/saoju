<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sc_job', function (Blueprint $table) {
            $table->integer('sc_id')->unsigned();
            $table->foreign('sc_id')->references('id')->on('scs');
            $table->integer('job_id')->unsigned();
            $table->primary(['sc_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sc_job');
    }
}
