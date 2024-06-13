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
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('idCustomer');
            $table->string('username',50);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('verification_token')->nullable();
            $table->string('PhoneNumber',20)->nullable();
            $table->string('Avatar')->nullable();            
            $table->boolean('email_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
