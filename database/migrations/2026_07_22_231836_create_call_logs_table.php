<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            // ارتباط با مشتری (اگر شماره ناشناس باشد، null می‌ماند)
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();

            $table->string('caller_id', 20)->index(); // شماره تماس گیرنده
            $table->string('extension', 10)->nullable(); // داخلی پاسخگو
            $table->string('did', 20)->nullable(); // شماره خط شرکت

            $table->enum('type', ['inbound', 'outbound', 'missed'])->default('inbound');
            $table->integer('duration')->default(0); // مدت زمان مکالمه به ثانیه
            $table->string('recording_file')->nullable(); // مسیر یا نام فایل ضبط شده در ایزابل
            $table->string('uniqueid')->nullable()->unique(); // شناسه یکتای تماس در استریسک (برای آپدیت‌های بعدی)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
