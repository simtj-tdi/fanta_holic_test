<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name',45)->comment('태그');
            $table->string('board',45)->nullable()->comment('게시물 종류 [ youtube / instagram / news / web ]');
            $table->string('type',20)->nullable()->comment('태그 종류 [ ori / custom ]');
            $table->unique(['name','board','type'],'tags_name_idx','BTREE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
