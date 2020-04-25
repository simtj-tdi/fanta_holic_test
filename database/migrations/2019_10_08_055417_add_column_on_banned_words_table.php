<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnBannedWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banned_words', function (Blueprint $table) {
            $table->string('app',20)->nullable()->comment('앱 이름');
            $table->dropUnique('banned_words_name_unique');
            $table->unique(['app','name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banned_words', function (Blueprint $table) {
            $table->dropUnique('banned_words_app_name_unique');
            $table->unique('name');
            $table->dropColumn('app');
        });
    }
}
