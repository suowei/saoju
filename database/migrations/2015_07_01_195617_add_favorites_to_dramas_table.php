<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class AddFavoritesToDramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dramas', function (Blueprint $table) {
            $table->integer('favorites')->after('reviews')->default(0);
        });
        DB::update('update dramas set favorites=(select count(*) from favorites where drama_id=dramas.id and deleted_at is null)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dramas', function (Blueprint $table) {
            $table->dropColumn('favorites');
        });
    }
}
