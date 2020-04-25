<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('유저 id');
            $table->string('device')->nullable()->comment('핸드폰 기종');
            $table->string('store_type')->nullable()->comment('마켓 타입 and ios');
            $table->string('os_version')->nullable()->comment('os version');
            $table->string('app_version')->nullable()->comment('app version');
            $table->string('fcm_token')->nullable()->comment('fcm_token');
            $table->string('device_key',100)->nullable()->comment('안드로이드 비회원 로그인용 키');
            $table->boolean('is_push')->default(false)->comment('알람 받으면 true 안받으면 false');

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
        Schema::dropIfExists('devices');
    }
}
