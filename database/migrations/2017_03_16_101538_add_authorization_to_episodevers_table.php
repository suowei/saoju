<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorizationToEpisodeversTable extends Migration
{
    public function up()
    {
        Schema::table('episodevers', function (Blueprint $table) {
            $table->text('authorization')->after('introduction')->nullable();
        });
    }

    public function down()
    {
        Schema::table('episodevers', function (Blueprint $table) {
            $table->dropColumn('authorization');
        });
    }
}
