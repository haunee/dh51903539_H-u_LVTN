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
        Schema::rename('attributevalue', 'propertyvalue');

        Schema::table('propertyvalue', function (Blueprint $table) {
            $table->renameColumn('idAttriValue', 'idProVal');
            $table->renameColumn('AttriValName', 'ProValName');
            $table->renameColumn('idAttribute', 'idProperty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propertyvalue', function (Blueprint $table) {
            //
        });
    }
};
