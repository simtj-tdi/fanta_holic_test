<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icerts', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('user_id')->unsigned()->comment('유저 id');
            $table->string('icert_name')->nullable()->comment('이름 / 본인확인');
            $table->string('icert_birthday')->nullable()->comment('생년월일 / 본인확인');
            $table->string('icert_mobile')->nullable()->comment('전화번호 / 본인확인');
            $table->string('icert_gender')->nullable()->comment('성별 / 본인확인');
            $table->string('icert_nation')->nullable()->comment('외국인 여부 / 본인확인 => 내국인:0 , 외국인:1');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('icerts');
    }
}
