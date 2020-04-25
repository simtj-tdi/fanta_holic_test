<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrawlerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', \App\Enums\ChannelType::getValues())->comment('채널 타입');
            $table->string('name', 30)->comment('채널 명');
            $table->json('auth')->comment('계정접속정보');
            $table->timestamp('finaled_at')->comment('마지막 접속시간');
            $table->string('term', 20)->comment('시간 텀');
            $table->enum('state', ['play', 'stop', 'wait'])->default('stop')->comment('채널 상태');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawler');
    }
}
