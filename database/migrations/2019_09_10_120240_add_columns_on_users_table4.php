<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOnUsersTable4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('sns_type',\App\Enums\UserSnsType::getValues())->default(\App\Enums\UserSnsType::SNS_NORMAL)->comment('소셜 로그인 타입');
            $table->string('sns_id')->nullable()->comment('소셜로그인 id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sns_type','sns_id']);
        });
    }
}
