<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserScoreLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_score_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app')->default('krieshachu')->comment('앱이름');
            $table->integer('user_id')->comment('유저 id');
            $table->integer('score')->comment('점수');
            $table->enum('type',['I','A','B'])->comment('점수획득종류 I: 아이템 사용 , A:인앱샵 ,상품구매 , B:게시물 작성');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_score_logs');
    }
}
