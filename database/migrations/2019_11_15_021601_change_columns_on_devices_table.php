<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsOnDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('streaming_push')->default(true)->comment('스티리밍 알람 받으면 true 안받으면 false')->change();
            $table->boolean('comment_push')->default(true)->comment('댓글 알람 받으면 true 안받으면 false')->change();
            $table->boolean('board_push')->default(true)->comment('게시물 승인 알람 받으면 true 안받으면 false')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('streaming_push')->default(false)->comment('스티리밍 알람 받으면 true 안받으면 false')->change();
            $table->boolean('comment_push')->default(false)->comment('댓글 알람 받으면 true 안받으면 false')->change();
            $table->boolean('board_push')->default(false)->comment('게시물 승인 알람 받으면 true 안받으면 false')->change();
        });
    }
}
