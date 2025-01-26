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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete("cascade");
            $table->string('state')->default("تهران");
            $table->string('city')->default("تهران");
            $table->string('address')->nullable();
            $table->string('no')->nullable();
            $table->string('floor')->nullable();
            $table->string('unit')->nullable();
            $table->string('municipality_zone')->nullable();
            $table->string('neighbourhood')->nullable();
            $table->text('description')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->tinyInteger('location_type')->default(0);
            $table->boolean('is_suggested')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
