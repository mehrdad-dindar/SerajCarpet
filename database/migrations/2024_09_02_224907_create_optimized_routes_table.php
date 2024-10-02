<?php

use App\Models\OptimizedRoute;
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
        Schema::create('optimized_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained();
            $table->json('orders');
            $table->tinyInteger('shift')
                ->default(OptimizedRoute::MORNING_SHIFT)
                ->comment('1 = Morning, 2 = Afternoon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('optimized_routes');
    }
};
