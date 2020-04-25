<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnUserScoreLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_score_logs', function (Blueprint $table) {
            $table->integer('music_id')->nullable()->comment('type = S 스트리밍 일때 music_id 값');
            $table->integer('board_id')->nullable()->comment('type = B 게시물 작성일때 board_id 값');
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
            $table->dropColumn(['music_id','board_id']);
        });
    }
}
