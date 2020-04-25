<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubeDeveloperKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_developer_keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->nullable()->comment('키');
            $table->tinyInteger('state')->default(0)->comment('키  사용가능여부 default =0  [ 0 : 사용불가 ,1 :사용가능] ');
            $table->integer('count')->default(0)->comment('키 사용량 [유투브 제한량 초기화시간(오후 3시나 4시)에 0으로 초기화]');
            $table->string('account')->nullable()->comment('해당키 계정');
            $table->string('password')->nullable()->comment('해당키 비밀번호');
            $table->string('comment')->nullable()->comment('현재 해당키 사용용도 설명 [ ex "크롤링" , "클라이언트 유투브 불러오기용" 등등]');
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
        Schema::dropIfExists('youtube_developer_keys');
    }
}
