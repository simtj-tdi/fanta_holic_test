<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\UserItemType;

class CreateUserItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app')->default('pinxy')->nullable()->comment('app 구분자');
            $table->string('user_id')->nullable()->comment('유저 id');
            $table->integer('item_count')->nullable()->comment('item 사용/충전 개수');
            $table->integer('board_id')->nullable()->comment('게시물 아이템 사용시 게시물 id');
            $table->enum('log_type',UserItemType::getValues())->default(UserItemType::BOARD_USE)->comment('게시물 아이템 사용:B');
            $table->string('description')->nullable()->comment('item 사용/충전 경로');
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
        Schema::dropIfExists('user_items');
    }
}
