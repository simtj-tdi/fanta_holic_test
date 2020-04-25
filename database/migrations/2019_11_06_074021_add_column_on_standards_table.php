<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('standards', function (Blueprint $table) {
            $table->string('item_point_count')->default(50)->comment('아이템 몇개 소모시 점수 지급 ex) 50 => 50개소모시 1점 지급');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('standards', function (Blueprint $table) {
            $table->dropColumn('item_point_count');
        });
    }
}
