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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('property_id')->nullable();
            $table->integer('dimensions')->default(1);
            $table->json('options')->nullable();
            $table->foreignId('carpet_color_id')->nullable();
            $table->integer('quantity');
            $table->string('unit_price');
            $table->string('sub_total');
            $table->string('title')->nullable();
            $table->boolean('is_custom')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
