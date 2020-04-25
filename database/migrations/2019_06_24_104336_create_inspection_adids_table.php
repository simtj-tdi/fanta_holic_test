<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionAdidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_adids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ad_id',100)->nullable()->unique()->comment('검수 ad_id');
            $table->string('comment')->nullable()->comment('ad_id 설명');
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
        Schema::dropIfExists('inspection_adids');
    }
}
