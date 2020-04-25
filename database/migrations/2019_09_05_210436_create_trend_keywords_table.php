<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrendKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trend_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app', 20);
            $table->string('keyword', 125);
            $table->timestamps();

            $table->index('app');
            $table->index('keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trend_keywords');
    }
}
