<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app', 20)->nullable()->comment('앱 구분자');
            $table->string('hashtag')->nullable()->comment('게시물 검색시 사용할 해시태그들 "," 로 구분');
            $table->string('title')->nullable()->comment('광고 제목');
            $table->char('event_type', 1)->nullable()->comment('광고 타입 [M=멜론 스트리밍 , I = 설치형, F= 친구초대, C =클릭형]');
            $table->integer('order_num')->default(0)->comment('광고 노출 순서');
            $table->string('img_url')->nullable()->comment('광고 이미지 url');
            $table->integer('repeat')->default(0)->comment('반복시간 [1시간 = 60 / 일회성 = 0]');
            $table->string('description')->nullable()->comment('이벤트 타입 설명');
            $table->string('url')->nullable()->comment('이벤트 실행할 이동 url');
            $table->string('app_package')->nullable()->comment('앱 패키지명');
            $table->integer('item_count')->default(0)->comment('광고 보상 아이템 개수');
            $table->string('push_title')->nullable()->comment('fcm push title');
            $table->string('psuh_message')->nullable()->comment('fcm push content');
            $table->tinyInteger('state')->default(0)->comment('게시여부 [1=게시/ 0=비게시]');
            $table->timestamp('start_date')->nullable()->comment('광고시작 시간');
            $table->timestamp('end_date')->nullable()->comment('광고 종료 시간');
            $table->string('thumbnail_1_1')->nullable()->comment('event_type = C일때 , 1x1 이미지 url');
            $table->string('thumbnail_2_1')->nullable()->comment('event_type = C일때 , 2x1 이미지 url');
            $table->string('thumbnail_3_1')->nullable()->comment('event_type = C일때 , 3x1 이미지 url');
            $table->string('thumbnail_1_2')->nullable()->comment('event_type = C일때 , 1x2 이미지 url');
            $table->string('thumbnail_2_2')->nullable()->comment('event_type = C일때 , 2x2 이미지 url');
            $table->string('thumbnail_3_3')->nullable()->comment('event_type = C일때 , 3x3 이미지 url');
            $table->softDeletes();
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
        Schema::dropIfExists('campaigns');
    }
}
