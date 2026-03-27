<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempt_answers';

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'selected_option_id',
        'given_answer',
        'is_correct',
        'points_earned',
        'ai_explanation',
    ];

    protected $casts = [
        'given_answer' => 'array',
        'is_correct' => 'boolean',
    ];

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}

