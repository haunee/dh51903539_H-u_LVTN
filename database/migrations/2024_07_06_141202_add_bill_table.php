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
        Schema::create('bill', function (Blueprint $table) {
            $table->increments('idBill');
            $table->integer('idCustomer');
            $table->string('Payment', 50)->default('cash');
           
            $table->string('Address');
            $table->string('PhoneNumber',20);
            $table->string('CustomerName',50);
            $table->dateTime('ReceiveDate')->nullable();
            $table->tinyInteger('Status')->default(0);
            $table->integer('TotalBill');
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
