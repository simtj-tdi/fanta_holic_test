<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('id');
            $table->Integer('user_id')->nullable()->unsigned()->comment('작성유저 id');
            $table->bigInteger('board_id')->nullable()->unsigned()->comment('게시글 id');
            $table->string('comment',255)->nullable()->comment('댓글내용');
            $table->integer('parent_id')->nullable()->comment('부못댓글id , 없으면 x');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('board_id')
                ->references('id')
                ->on('boards')
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
        Schema::dropIfExists('comments');
    }
}
