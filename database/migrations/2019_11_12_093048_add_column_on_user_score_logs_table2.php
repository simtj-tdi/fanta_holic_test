<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnUserScoreLogsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_score_logs', function (Blueprint $table) {
            $table->integer('item_board_id')->nullable()->comment('type = I 아이템 사용 일때 board_id 값');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_score_logs', function (Blueprint $table) {
            $table->dropColumn('item_board_id');
        });
    }
}
