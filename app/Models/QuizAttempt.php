<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'time_spent_seconds',
        'status',           // in_progress | completed | passed | failed | expired
        'ai_feedback',
        'started_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'score'        => 'float',
            'started_at'   => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    // Alias expected by views/controllers: questionAnswers
    public function questionAnswers(): HasMany
    {
        return $this->answers();
    }

    public function getIsPassedAttribute(): bool
    {
        return $this->score !== null
            && $this->quiz
            && $this->score >= $this->quiz->passing_score;
    }

    public function getTimeSpentFormattedAttribute(): string
    {
        $seconds = $this->time_spent_seconds ?? 0;

        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }
}
