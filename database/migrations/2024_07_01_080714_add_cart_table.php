<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('idCart');
            $table->integer('idCustomer');
            $table->integer('idProduct');
            $table->integer('idProperPro');
            $table->string('PropertyPro',50);
            $table->integer('Price');
            $table->integer('QuantityBuy');
            $table->integer('Total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
