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
        Schema::create('addresscustomer', function (Blueprint $table) {
            $table->increments('idAddress');
            $table->integer('idCustomer');
            $table->string('Address');
            $table->string('PhoneNumber',20);
            $table->string('CustomerName',50);
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
