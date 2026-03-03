<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_id',
        'user_id',
        'chosen_choice_id',
        'safety_score',
        'ai_feedback',
        'time_spent_seconds',
    ];

    public function scenario()
    {
        return $this->belongsTo(Scenario::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chosenChoice()
    {
        return $this->belongsTo(ScenarioChoice::class, 'chosen_choice_id');
    }
}
