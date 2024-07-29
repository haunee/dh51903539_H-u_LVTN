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
        Schema::table('orderhistory', function (Blueprint $table) {
            // Thêm cột idAdmin
            $table->unsignedInteger('idAdmin')->nullable()->after('AdminName');

            // Thêm khóa ngoại liên kết với bảng admin
            $table->foreign('idAdmin')
                  ->references('idAdmin')
                  ->on('admin')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orderhistory', function (Blueprint $table) {
             // Xóa khóa ngoại
             $table->dropForeign(['idAdmin']);
            
             // Xóa cột idAdmin
             $table->dropColumn('idAdmin');
        });
    }
};
