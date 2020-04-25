<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserItemAccumulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_item_accumulations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app')->comment('앱이름');
            $table->integer('user_id')->comment('유저 id');
            $table->integer('item_count')->default(0)->comment('누적 아이템 사용수 기준개수 달성시 점수 지급후 0으로 초기화');
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
        Schema::dropIfExists('user_item_accumulations');
    }
}
