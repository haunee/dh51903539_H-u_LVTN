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
        Schema::create('orderdetail', function (Blueprint $table) {
            $table->integer('idOrder');
            $table->integer('idProduct');
            $table->string('AttributeProduct', 50);
            $table->integer('Price');
            $table->integer('QuantityBuy');
            $table->integer('idProAttr'); // Thêm cột idProAttr
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
