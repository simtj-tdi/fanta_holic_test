<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnOnBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //Doctrine issue change 안먹힘 => 시간상 쌩쿼리 수정
    public function up()
    {
//        Schema::table('boards', function (Blueprint $table) {
//            $table->integer('item_count')->default(0)->change();
//        });
        DB::statement('ALTER TABLE boards MODIFY item_count integer;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('boards', function (Blueprint $table) {
//            $table->string('item_count')->default(0)->change();
//        });
        DB::statement('ALTER TABLE boards MODIFY item_count varchar;');
    }
}
