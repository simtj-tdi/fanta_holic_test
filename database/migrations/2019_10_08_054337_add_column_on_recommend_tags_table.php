<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnRecommendTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommend_tags', function (Blueprint $table) {
            $table->string('app',20)->nullable()->comment('앱 이름');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommend_tags', function (Blueprint $table) {
            $table->dropColumn('app');
        });
    }
}
