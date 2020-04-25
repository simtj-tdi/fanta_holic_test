<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account')->comment('account')->nullable();
            $table->string('ad_id')->comment('Device ad_id');
            $table->string('app')->comment('로그인 App');
            $table->string('device')->comment('로그인 Device');
            $table->string('ip')->comment('로그인 IP');
            $table->string('os_version')->comment('OS Version');
            $table->string('store_type')->comment('OS');
            $table->bigInteger('user_id')->comment('user_id');
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
        Schema::dropIfExists('user_login_histories');
    }
}
