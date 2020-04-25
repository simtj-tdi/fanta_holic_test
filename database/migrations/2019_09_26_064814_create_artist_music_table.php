<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_music', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('artist_id')->unsigned()->comment('가수 id');
            $table->bigInteger('music_id')->unsigned()->comment('음악 id');
            $table->foreign('artist_id')
                ->references('id')
                ->on('artists')
                ->onDelete('cascade');
            $table->foreign('music_id')
                ->references('id')
                ->on('musics')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artist_music');
    }
}
