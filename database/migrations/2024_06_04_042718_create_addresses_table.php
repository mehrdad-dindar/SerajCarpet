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
            $table->foreignId("customer_id")->constrained()->onDelete("cascade");
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->boolean('in_traffic_zone')->default(false);
            $table->boolean('in_odd_even_zone')->default(false);
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
