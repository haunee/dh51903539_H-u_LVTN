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
        Schema::rename('attribute', 'property');

        Schema::table('property', function (Blueprint $table) {
            $table->renameColumn('idAttribute', 'idProperty');
            $table->renameColumn('AttributeName', 'PropertyName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property', function (Blueprint $table) {
            //
        });
    }
};
