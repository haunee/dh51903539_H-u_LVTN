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
        Schema::table('reset_password_customer', function (Blueprint $table) {
            $table->unsignedInteger('idCustomer')->nullable()->after('token');
            
            // Add foreign key constraint
            $table->foreign('idCustomer')
                  ->references('idCustomer')
                  ->on('customer')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reset_password_customer', function (Blueprint $table) {
            $table->dropForeign(['idCustomer']);
            $table->dropColumn('idCustomer');
        });
    }
};
