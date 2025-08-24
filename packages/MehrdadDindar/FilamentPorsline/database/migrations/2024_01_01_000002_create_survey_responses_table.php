<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('porsline_response_id')->unique();
            $table->string('responder_id')->nullable();
            $table->string('responder_email')->nullable();
            $table->string('responder_phone')->nullable();
            $table->string('responder_name')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('submit_time')->nullable();
            $table->timestamp('last_edit_time')->nullable();
            $table->integer('score')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_spam')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
}; 