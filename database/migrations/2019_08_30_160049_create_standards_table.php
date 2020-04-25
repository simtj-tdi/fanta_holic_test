<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standards', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('app')->nullable()->comment('app 구분자');
            $table->integer('spamming')->default(30)->comment('댓글도배행위 판단 기준 기본값 30초 ex)30초내 댓글 5개이상 = 도배');
            $table->integer('spam_count')->default(1)->comment('댓글도배행위 몇번일시 기본값 1 경고 ex)1회 댓글도배행위 포착시 경고');
            $table->integer('blind_count')->default(3)->comment('경고 몇번 이상시 블라인드처리 기본값 3 ex) 경고 3회시 앱 사용 잠시차단');
            $table->integer('black_count')->default(3)->comment('블라인드경고 몇번부터 블랙리스트 처리 기본값 3 ex)블라인드3번이후 해당유저 블랙리스트 처리');
            $table->integer('comment_like_score')->default(10)->comment('댓글 좋아요시 점수 기본값 10');
            $table->integer('article_like_score')->default(10)->comment('게시물 좋아요시 점수 기본값 10');
            $table->integer('comment_score')->default(10)->comment('댓글작성시 점수 기본값 10');
            $table->integer('login_reward')->default(10)->comment("출석보상 아이템 개수");
            $table->json('ranking')->nullable()->comment('랭킹 기준');
            $table->timestamps();
            $table->index('app');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standards');
    }
}
