<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('email')->nullable()->comment('이메일');
            $table->datetime('email_verified_at')->nullable()->comment('이메일 인증');
            $table->string('password')->nullable()->comment('비밀번호');
            $table->dateTime('identity_verfied_at')->nullable()->comment('본인인증 유무');//identity_verfied_at
            $table->dateTime('mobile_verified_at')->nullable()->comment('휴대폰인증 유무');//identity_verfied_at
            $table->string('birth')->nullable()->comment('생년월일');
            $table->tinyInteger('gender')->nullable()->comment('성별');
            $table->string('mobile')->nullable()->comment('휴대폰 번호');
            $table->string('name')->nullable()->comment('이름');
            $table->string('nickname')->nullable()->comment('닉네임');
            $table->dateTime('last_logged_at')->nullable()->comment('마지막 로그인');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
