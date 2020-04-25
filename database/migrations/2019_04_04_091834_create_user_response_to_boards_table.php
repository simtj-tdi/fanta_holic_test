<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserResponseToBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_response_to_boards', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('user_id')->unsigned()->comment('유저 id');
                $table->bigInteger('board_id')->unsigned()->comment('게시물 id');
                $table->tinyInteger('response')->unsigned()->default(1)->comment('유저 반응 1:좋아요 0 싫어요');
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_response_to_boards');
    }
}
