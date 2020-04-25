<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app', 20);
            $table->string('type', 25)->default('request');
            $table->string('status', 35)->default('pending');
            $table->string('category', 255)->nullable();
            $table->text('contents');
            $table->timestamps();

            $table->index('app');
            $table->index('type');
            $table->index('status');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_requests');
    }
}
