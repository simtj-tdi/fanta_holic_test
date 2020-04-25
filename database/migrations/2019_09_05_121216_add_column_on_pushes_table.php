<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnPushesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pushes', function (Blueprint $table) {
            $table->integer('campaign_id')->nullable()->comment('광고 관련 push 일경우 광고 id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pushes', function (Blueprint $table) {
            $table->dropColumn('campaign_id');
        });
    }
}
