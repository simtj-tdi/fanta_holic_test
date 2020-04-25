<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pushes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app',20)->nullable()->comment('fcm 보낼 앱');
            $table->char('batch_type',1)->nullable()->default('C')->comment('발송 타입 [A=전체 발송/ P = 개인 발송]');
            $table->char('managed_type',1)->nullable()->default('M')->comment('관리용 타입 [M = 관리자 등록 / N = 새게시물 등록 / C = 대댓글 알림]');
            $table->tinyInteger('new_post_count')->unsigned()->nullable()->default(0)->comment('batch_type = N 일 때,등록된 게시물 개수');
            $table->integer('user_id')->nullable()->default(0)->comment('개인발송 일 때 발송할 유저 아이디');
            $table->string('title',50)->nullable()->comment('fcm title');
            $table->string('content',150)->nullable()->comment('fcm content');
            $table->string('tick',50)->nullable()->comment('fcm tick');
            $table->char('push_type',1)->nullable()->default('T')->comment('푸시 종류 [T=text/ I=image]');
            $table->string('img_url',255)->nullable()->default(null)->comment('push_type = image 일 때 ,배너 이미지 url');
            $table->char('action',1)->nullable()->default('A')->comment('행동 종류 [M=이동,A=앱실행,B=특정 게시물로 이동]');
            $table->string('url',255)->nullable()->default(null)->comment('action= M 일때, 이동할 url');
            $table->string('board_type',10)->nullable()->default(null)->comment('action=B 일 때 ,게시물 타입(ads,notice,new,instagram,youtube ...]');
            $table->integer('board_id')->nullable()->default(null)->comment('action = B 일 때 ,게시물 id');
            $table->char('state',1)->nullable()->default('R')->comment('push 진행 상태 [R=대기,S=발송중,Y=발송완료/X=발송취소]');
            $table->integer('success')->unsigned()->default(0);
            $table->integer('fail')->unsigned()->default(0);
            $table->timestamp('start_date')->comment('발송 시작 시간');
            $table->string('streaming_url')->comment('action = S일 때 url');
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
        Schema::dropIfExists('pushes');
    }
}
