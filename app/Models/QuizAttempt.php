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
        'total_points',
        'percentage',
        'passed',
        'time_spent_seconds',
        'status',
        'ai_feedback',
        'started_at',
        'submitted_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'score'        => 'float',
            'total_points' => 'float',
            'percentage'   => 'float',
            'passed'       => 'boolean',
            'started_at'   => 'datetime',
            'submitted_at' => 'datetime',
            'completed_at' => 'datetime',
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

    public function questionAnswers(): HasMany
    {
        return $this->answers();
    }

    public function getIsPassedAttribute(): bool
    {
        if (! is_null($this->passed)) {
            return (bool) $this->passed;
        }

        return $this->score !== null
            && $this->quiz
            && $this->score >= $this->quiz->passing_score;
    }

    public function getTimeSpentFormattedAttribute(): string
    {
        $seconds = $this->time_spent_seconds;

        if ($seconds === null && $this->started_at && $this->completed_at) {
            $seconds = $this->completed_at->diffInSeconds($this->started_at);
        }

        $seconds = $seconds ?? 0;

        $hours            = intdiv($seconds, 3600);
        $minutes          = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }
}
