<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name');
            $table->integer('userId');
            $table->string('last_name');
            $table->string('email');
            $table->text('items_purchased')->nullable();
            $table->string('billing_country');
            $table->string('billing_postal_code');
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_street_address');
            $table->string('shipping_country');
            $table->string('shipping_postal_code');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_street_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
