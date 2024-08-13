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
        // Đổi tên cột trong bảng 'cart'
        Schema::table('cart', function (Blueprint $table) {
            $table->renameColumn('idProAttr', 'idProperPro');
        });

        // Đổi tên cột trong bảng 'orderdetail'
        Schema::table('orderdetail', function (Blueprint $table) {
            $table->renameColumn('idProAttr', 'idProperPro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại tên cột về 'idProAttr' trong bảng 'cart'
        Schema::table('cart', function (Blueprint $table) {
            $table->renameColumn('idProperPro', 'idProAttr');
        });

        // Khôi phục lại tên cột về 'idProAttr' trong bảng 'orderdetail'
        Schema::table('orderdetail', function (Blueprint $table) {
            $table->renameColumn('idProperPro', 'idProAttr');
        });
    }
};
