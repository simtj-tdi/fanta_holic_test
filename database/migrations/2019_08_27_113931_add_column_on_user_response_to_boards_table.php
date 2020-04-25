<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnUserResponseToBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_response_to_boards', function (Blueprint $table) {
            $table->string('app')->nullable()->default('pinxy')->comment('앱 구분자');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_response_to_boards', function (Blueprint $table) {
            $table->dropColumn('app');
        });
    }
}
