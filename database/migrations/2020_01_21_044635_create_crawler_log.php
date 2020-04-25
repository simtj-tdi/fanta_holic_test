<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrawlerLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_logs', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->char('status',1)->comment('상태값 [S= 성공, F = 실패]');
          $table->integer('crawler_cnt')->unsigned()->default(0)->comment('크롤링 컨텐츠 갯수');
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
        Schema::dropIfExists('crawler_logs');
    }
}
