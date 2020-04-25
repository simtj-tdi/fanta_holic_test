<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnIcertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('icerts', function (Blueprint $table) {
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
        Schema::table('icerts', function (Blueprint $table) {
            $table->dropColumn('app');
        });
    }
}
