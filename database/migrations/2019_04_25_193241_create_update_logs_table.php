<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_logs', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('board_id')->nullable()->comment('board_id');
            $table->string('board_type')->nullable()->comment('board 종류 instagram,youtube,web,news ');
            $table->string('update_name')->nullable()->comment('update 종류 개시,내림,남자,여자,검수등록,검수해제,B태그수정,삭제');
            $table->string('prev_tag')->nullable()->comment('태그수정일시 기존B태그들');
            $table->string('after_tag')->nullable()->comment('태그수정일시 수정후B태그들');
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
        Schema::dropIfExists('update_logs');
    }
}
