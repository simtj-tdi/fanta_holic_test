<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app',20)->comment('앱 이름');
            $table->char('type',1)->nullable()->default('A')->comment('공지타입 [A=전체/ P = 개인]');
            $table->char('managed_type',1)->nullable()->default('M')->comment('관리용 타입 [M = 관리자 등록 / N = 새게시물 등록 / C = 대댓글 알림]');
            $table->integer('user_id')->nullable()->default(0)->comment('개인공지 일 때 유저 아이디');
            $table->string('thumbnail_url',255)->comment('썸네일 url');
            $table->string('title')->nullable()->comment('공지 제목');
            $table->string('contents')->nullable()->comment('공지 내용');
            $table->json('data')->nullable()->comment('게시물 데이터');
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
        Schema::dropIfExists('notices');
    }
}
