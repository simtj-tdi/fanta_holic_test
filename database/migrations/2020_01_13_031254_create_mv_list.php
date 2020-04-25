<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mv_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('boards_id')->unsigned()->comment('앨범 id');
            $table->foreign('boards_id')
                ->references('id')
                ->on('boards');
            $table->char('status',1)->default('Y')->comment('노출 Y,N');
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
        Schema::dropIfExists('mv_list');
    }
}
