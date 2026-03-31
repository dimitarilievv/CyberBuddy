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
        // Add missing columns to quiz_attempt_answers if they don't exist yet
        if (!Schema::hasTable('quiz_attempt_answers')) {
            // If table doesn't exist (unexpected), create it with full schema
            Schema::create('quiz_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
                $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
                $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
                $table->json('given_answer')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('points_earned')->nullable();
                $table->text('ai_explanation')->nullable();
                $table->timestamps();
            });

            return;
        }

        // Table exists; add missing columns individually
        if (!Schema::hasColumn('quiz_attempt_answers', 'quiz_attempt_id')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->foreignId('quiz_attempt_id')->after('id')->constrained('quiz_attempts')->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'question_id')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->foreignId('question_id')->after('quiz_attempt_id')->constrained('questions')->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'selected_option_id')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->foreignId('selected_option_id')->nullable()->after('question_id')->constrained('question_options')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'given_answer')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->json('given_answer')->nullable()->after('selected_option_id');
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'is_correct')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->boolean('is_correct')->default(false)->after('given_answer');
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'points_earned')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->integer('points_earned')->nullable()->after('is_correct');
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'ai_explanation')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->text('ai_explanation')->nullable()->after('points_earned');
            });
        }

        if (!Schema::hasColumn('quiz_attempt_answers', 'created_at')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('quiz_attempt_answers')) {
            return;
        }

        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempt_answers', 'ai_explanation')) {
                $table->dropColumn('ai_explanation');
            }
            if (Schema::hasColumn('quiz_attempt_answers', 'points_earned')) {
                $table->dropColumn('points_earned');
            }
            if (Schema::hasColumn('quiz_attempt_answers', 'is_correct')) {
                $table->dropColumn('is_correct');
            }
            if (Schema::hasColumn('quiz_attempt_answers', 'given_answer')) {
                $table->dropColumn('given_answer');
            }
            if (Schema::hasColumn('quiz_attempt_answers', 'selected_option_id')) {
                $table->dropForeign(['selected_option_id']);
                $table->dropColumn('selected_option_id');
            }
            if (Schema::hasColumn('quiz_attempt_answers', 'question_id')) {
                $table->dropForeign(['question_id']);
                $table->dropColumn('question_id');
            }
            if (Schema::hasColumn('quiz_attempt_answers', 'quiz_attempt_id')) {
                $table->dropForeign(['quiz_attempt_id']);
                $table->dropColumn('quiz_attempt_id');
            }
        });
    }
};

