<?php
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
//            $table->id();
//            $table->timestamps();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('quiz_attempt_answers');
//    }
//};


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // IMPORTANT:
        // If the table already exists in your DB, comment out this block to avoid duplicate-table errors.
        if (!Schema::hasTable('quiz_attempt_answers')) {
            Schema::create('quiz_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('quiz_attempt_id');
                $table->unsignedBigInteger('question_id');
                $table->unsignedBigInteger('selected_option_id')->nullable();
                $table->json('given_answer')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('points_earned')->default(0);
                $table->json('ai_explanation')->nullable();
                $table->timestamps();

                $table->index('quiz_attempt_id');
                $table->index('question_id');
                $table->index('selected_option_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
};
