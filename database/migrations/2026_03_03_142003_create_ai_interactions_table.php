<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['chat', 'scenario_generation', 'quiz_help', 'content_explanation', 'safety_check']);
            $table->text('prompt');
            $table->longText('response');
            $table->string('model_used')->default('gemini-2.5-flash');
            $table->integer('tokens_used')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->boolean('was_helpful')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_interactions');
    }
};
