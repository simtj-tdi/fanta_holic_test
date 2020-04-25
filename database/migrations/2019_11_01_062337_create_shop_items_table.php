<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app')->comment('앱이름');
            $table->string('url')->comment('상품 url');
            $table->string('thumbnail_url')->comment('썸네일 url');
            $table->unsignedInteger('thumbnail_w')->comment('썸네일 가로길이');
            $table->unsignedInteger('thumbnail_h')->comment('썸네일 세로길이');
            $table->string('title')->comment('상품명');
            $table->unsignedInteger('price')->comment('가격');
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
        Schema::dropIfExists('shop_items');
    }
}
