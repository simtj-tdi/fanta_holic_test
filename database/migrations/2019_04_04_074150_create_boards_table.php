<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app',20)->comment('앱 이름');
            $table->enum('type',['event','fanfeed','instagram','myfeed','news','twitter','vlive','youtube'])->comment('게시물 타입 [ youtube / instagram / news / web ]');
            $table->string('post',100)->nullable()->comment('원본 주소 (youtube or instagram)');
            $table->string('post_type',100)->nullable()->comment('instagram 게시물 타입  [ image / Post / video ] / youtube 일때 채널 아이디');
            $table->string('thumbnail_url',255)->comment('썸네일 url');
            $table->integer('thumbnail_w')->unsigned()->default(0)->comment('썸네일 width');
            $table->integer('thumbnail_h')->unsigned()->default(0)->comment('썸네일 height');
            $table->string('title',255)->nullable()->comment('제목');
            $table->text('contents')->nullable()->comment('내용');
            $table->string('sns_account',100)->nullable()->comment('작성자');
            $table->json('ori_tag')->nullable()->comment('해시태그');
            $table->json('custom_tag')->nullable()->comment('관리자 추가 태그');
            $table->json('data')->nullable()->comment('게시물 데이터');
            $table->string('ori_thumbnail',255)->nullable()->comment('original 썸네일 url');
            $table->json('ori_data')->nullable()->comment('original 게시물 데이터');
            $table->tinyInteger('gender')->unsigned()->default(1)->comment('성별 (1:남자 / 2:여자 )');
            $table->tinyInteger('state')->unsigned()->default(0)->comment('게시 여부 ( 0:숨김 / 1:오픈 )');
            $table->tinyInteger('deleted')->unsigned()->default(0)->comment('삭제 ( 0:미삭제 / 1:삭제 )');
            $table->integer('created_date')->unsigned()->default(0)->comment('게시물 업로드 시간');
            $table->integer('updated_date')->unsigned()->default(0)->comment('게시물 수정 시간');
            $table->tinyInteger('text_check')->unsigned()->default(0)->comment('택스트 여부 (0:미검수 1:없음 2:있음');
            $table->string('search_type',25)->nullable()->comment('크롤링 검색 타입 [ hashtag / account / keyword ]');
            $table->string('search',50)->nullable()->comment('크롤링 한 검색어');
            $table->tinyInteger('app_review')->unsigned()->default(0)->comment( '검수용 컨텐츠 구분 ( 0: 원래 컨텐츠 / 1: 검수용 컨텐츠 )');
            $table->unique(['app','type','post'],'boards_unique_idx','BTREE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
}
