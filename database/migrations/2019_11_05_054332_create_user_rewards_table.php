<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app')->comment('앱이름');
            $table->integer('campaign_id')->nullable()->comment('캠페인 id');
            $table->integer('board_id')->nullable()->comment('게시물 id');
            $table->integer('user_id')->comment('유저 id');
            $table->enum('log_type',['B','A','D','C'])->comment('게시물 작성:B ,인앱샵 구매:A, 출석보상:D, 캠페인 보상:C');
            $table->string('description')->nullable()->comment('item 충전 경로 설명');
            $table->integer('item_count')->comment('지급한 아이템 개수');
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
        Schema::dropIfExists('user_rewards');
    }
}
