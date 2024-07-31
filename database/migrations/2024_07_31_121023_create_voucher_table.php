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
        Schema::create('voucher', function (Blueprint $table) {
            $table->increments('idVoucher'); // Tạo cột tự động tăng idVoucher
            $table->string('VoucherName'); // Tên của voucher
            $table->integer('VoucherQuantity'); // Số lượng voucher
            $table->tinyInteger('VoucherCondition');//ĐIỀU KIỆN % HAY GIẢM TIỀN 
            $table->string('VoucherNumber'); // Số lượng voucher còn lại
            $table->string('VoucherCode', 50)->unique(); // Mã voucher
            $table->dateTime('VoucherStart'); // Ngày bắt đầu
            $table->dateTime('VoucherEnd'); // Ngày kết thúc
            $table->timestamps(); // Cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};
