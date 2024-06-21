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
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('idAdmin');      
            $table->string('AdminUser',50);
            $table->string('AdminPass');
            $table->integer('NumberPhone')->nullable();
            $table->string('AdminName',50)->nullable();
            $table->string('Email',50)->nullable();
            $table->string('Address')->nullable();
            $table->string('Avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
