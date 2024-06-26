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
        Schema::create('product', function (Blueprint $table) {
            $table->Increments('idProduct');
            $table->integer('idCategory');
            $table->integer('idBrand');
            $table->integer('QuantityTotal');
            $table->string('ProductName');
            $table->longText('DesProduct');
            $table->text('ShortDes');
            $table->integer('Price');
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
