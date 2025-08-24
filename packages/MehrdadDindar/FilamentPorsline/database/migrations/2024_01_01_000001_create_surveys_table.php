<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('porsline_id')->unique();
            $table->string('name');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('submitted_responses')->default(0);
            $table->string('preview_code')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
