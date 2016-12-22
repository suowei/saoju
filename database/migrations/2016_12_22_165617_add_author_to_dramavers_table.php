<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorToDramaversTable extends Migration
{
    public function up()
    {
        Schema::table('dramavers', function (Blueprint $table) {
            $table->string('author')->after('original');
        });
    }

    public function down()
    {
        Schema::table('dramavers', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }
}
