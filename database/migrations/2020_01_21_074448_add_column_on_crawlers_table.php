<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnCrawlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crawlers', function (Blueprint $table) {
            $table->Integer('artists_id')->unsigned()->comment('아티스트 아이디');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crawlers', function (Blueprint $table) {
            $table->dropColumn('artists_id');
        });
    }
}
