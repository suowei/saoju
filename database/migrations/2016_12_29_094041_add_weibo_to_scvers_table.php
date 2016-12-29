<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeiboToScversTable extends Migration
{
    public function up()
    {
        Schema::table('scvers', function (Blueprint $table) {
            $table->string('weibo')->after('jobs')->nullable();
        });
    }

    public function down()
    {
        Schema::table('scvers', function (Blueprint $table) {
            $table->dropColumn('weibo');
        });
    }
}
