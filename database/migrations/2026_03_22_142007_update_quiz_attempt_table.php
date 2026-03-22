<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update quiz_attempts table instead of creating it
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // Example: add new columns if needed, or modify existing ones
            if (!Schema::hasColumn('quiz_attempts', 'ai_feedback')) {
                $table->text('ai_feedback')->nullable()->after('status');
            }
            if (!Schema::hasColumn('quiz_attempts', 'status')) {
                $table->string('status', 20)->default('in_progress')->index()->after('time_spent_seconds');
            }
            if (!Schema::hasColumn('quiz_attempts', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('started_at');
            }
            // Add any other columns or indexes as needed
        });

        // Create quiz_attempt_answers table if it does not exist
        if (!Schema::hasTable('quiz_attempt_answers')) {
            Schema::create('quiz_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_attempt_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->foreignId('question_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->foreignId('selected_option_id')
                    ->nullable()
                    ->constrained('question_options')
                    ->nullOnDelete();
                $table->boolean('is_correct')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop quiz_attempt_answers table if exists
        Schema::dropIfExists('quiz_attempt_answers');
        // Optionally, drop added columns from quiz_attempts
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempts', 'ai_feedback')) {
                $table->dropColumn('ai_feedback');
            }
            if (Schema::hasColumn('quiz_attempts', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('quiz_attempts', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
        });
    }
};
