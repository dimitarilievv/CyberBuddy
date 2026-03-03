<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'modules_completed',
        'quizzes_passed',
        'scenarios_completed',
        'badges_earned',
        'current_streak',
        'longest_streak',
        'rank',
        'period',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
