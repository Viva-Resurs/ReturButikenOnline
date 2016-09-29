<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('thumb_path');

            $table->timestamps();
        });

        Schema::create('images_article', function (Blueprint $table) {

            $table->integer('images_id')->unsigned()->index();
            $table->foreign('images_id')->references('id')->on('images')->onDelete('cascade');
            $table->integer('article_id')->unsigned()->index();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');

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
        Schema::drop('images');
        Schema::drop('images_article');
    }
}
