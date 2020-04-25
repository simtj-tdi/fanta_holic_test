<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app', 20);
            $table->string('package', 125);
            $table->string('status', 25)->default('used');
            $table->date('date');
            $table->bigInteger('count')->default(0);
            $table->json('detail')->nullable();
            $table->timestamps();

            $table->index('app');
            $table->index('package');
            $table->index('status');
            $table->index('date');

            $table->unique(['app', 'status', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_stats');
    }
}
