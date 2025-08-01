<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_configs', function (Blueprint $table) {
            $table->id();
            $table->string('porsline_form_id')->nullable();
            $table->string('sms_pattern_code')->nullable();
            $table->unsignedInteger('send_after_days')->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_configs');
    }
};
