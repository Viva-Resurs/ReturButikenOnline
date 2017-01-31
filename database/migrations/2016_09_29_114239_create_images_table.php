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
            $table->integer('order')->nullable();

            $table->timestamps();
        });

        Schema::create('images_user', function (Blueprint $table) {

            $table->integer('image_id')->unsigned()->index();
            $table->foreign('image_id')->references('id')->on('image')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');

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
        Schema::drop('images_user');
    }
}
