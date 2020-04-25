<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrendKeywordStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trend_keyword_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 25)->default('naver');
            $table->string('keyword', 125);
            $table->date('date');
            $table->bigInteger('pc_count')->default(0);
            $table->bigInteger('mobile_count')->default(0);
            $table->timestamps();

            $table->index('type');
            $table->index('keyword');
            $table->index('date');

            $table->unique(['type', 'keyword', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trend_keyword_stats');
    }
}
