<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained();
            $table->foreignId('address_id')->nullable()->constrained()->onDelete("SET NULL");
            $table->string('discount')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('total')->nullable();
            $table->boolean('in_person_delivery')->nullable();
            $table->foreignId('status_id')->constrained("order_statuses")->nullable();
            $table->dateTime('time_apply_status')->nullable();
            $table->dateTime('collected_at')->nullable();
            $table->dateTime('sent_to_factory_at')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 10001;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
