<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorToDramasTable extends Migration
{
   public function up()
    {
        Schema::table('dramas', function (Blueprint $table) {
            $table->string('author')->after('original');
        });
    }

    public function down()
    {
        Schema::table('dramas', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }
}
