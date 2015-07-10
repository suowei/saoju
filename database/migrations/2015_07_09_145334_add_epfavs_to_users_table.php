<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEpfavsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('epfav0')->after('favorite4')->default(0);
            $table->integer('epfav2')->after('epfav0')->default(0);
            $table->integer('epfav4')->after('epfav2')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('epfav0');
            $table->dropColumn('epfav2');
            $table->dropColumn('epfav4');
        });
    }
}
