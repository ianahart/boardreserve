<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnowboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snowboards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('brand');
            $table->string('model');
            $table->string('shape');
            $table->integer('size');
            $table->string('image')->nullable();
            $table->string('category');
            $table->longText('desc');
            $table->string('price');
            $table->integer('seller');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snowboards');
    }
}
