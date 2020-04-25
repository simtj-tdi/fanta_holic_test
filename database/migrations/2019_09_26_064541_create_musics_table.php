<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('album_id')->unsigned()->comment('앨범 id');
            $table->string('app')->nullable()->comment('앱이름');
            $table->string('title')->nullable()->comment('노래제목');
            $table->string('thumbnail_url')->comment('썸네일 이미지 url');
            $table->integer('repeat')->default(0)->comment('보상 반복 시간 텀(분), 일회성 =0');
            $table->integer('reward_count')->default(10)->comment('멜론 스트리밍 듣기 후 보상개수');
            $table->string('mv_url')->nullable()->comment('유투브 뮤직비디오 url');
            $table->string('melon_url')->nullable()->comment('멜론 url');
            $table->string('push_title')->nullable()->comment('보상지급 푸시 알람시 제목');
            $table->string('push_content')->nullable()->comment('보상지급 푸시 알람시 내용');
            $table->string('push_tick')->nullable()->comment('보상지급 푸시 알람시 틱');
            $table->tinyInteger('state')->default(0)->comment('게시 1 비게시 0 ');
            $table->timestamp('start_date')->nullable()->comment('기간정해서 게시할시 시작일');
            $table->timestamp('end_date')->nullable()->comment('기간정해서 게시할시 종료일');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('album_id')
                ->references('id')
                ->on('albums')
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
        Schema::dropIfExists('musics');
    }
}
