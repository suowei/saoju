<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorizationToEpisodesTable extends Migration
{
    public function up()
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->text('authorization')->after('introduction')->nullable();
        });
    }

    public function down()
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropColumn('authorization');
        });
    }
}
