<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app')->nullable()->comment('앱이름');
            $table->string('board')->nullable()->comment('music:음원페이지용, notice:공지페이지용');
            $table->string('img_url')->nullable()->comment('이미지 url');
            $table->integer('img_w')->default(0)->comment('이미지 width값');
            $table->integer('img_h')->default(0)->comment('이미지 height값');
            $table->integer('order_num')->nullable()->comment('게시 순서');
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
        Schema::dropIfExists('banners');
    }
}
