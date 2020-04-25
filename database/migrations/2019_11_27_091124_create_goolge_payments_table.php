<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoolgePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('유저정보');
            $table->string('product_id',30)->comment('상품 ID');
            $table->boolean('state')->comment('결제상태 0: 결제완료, 1:환불, 2:결제대기');
            $table->string('order_id')->comment('주문번호');
            $table->string('purchase_token')->comment('영수증 토큰')->unique();
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
        Schema::dropIfExists('google_payments');
    }
}
