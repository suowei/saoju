<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeiboToScsTable extends Migration
{
    public function up()
    {
        Schema::table('scs', function (Blueprint $table) {
            $table->string('weibo')->after('jobs')->nullable();
        });
    }

    public function down()
    {
        Schema::table('scs', function (Blueprint $table) {
            $table->dropColumn('weibo');
        });
    }
}
