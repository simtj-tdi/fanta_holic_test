<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsOnBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boards',function(Blueprint $table){
            $table->dropColumn(['deleted','created_date','updated_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boards',function (Blueprint $table) {
            $table->tinyInteger('deleted')->unsigned()->default(0)->comment('삭제 ( 0:미삭제 / 1:삭제 )');
            $table->integer('created_date')->unsigned()->default(0)->comment('게시물 업로드 시간');
            $table->integer('updated_date')->unsigned()->default(0)->comment('게시물 수정 시간');
        });
    }
}
