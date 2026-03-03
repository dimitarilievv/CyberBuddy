<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'situation',
        'difficulty',
        'age_group',
        'is_ai_generated',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_ai_generated' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function choices()
    {
        return $this->hasMany(ScenarioChoice::class)->orderBy('sort_order');
    }

    public function attempts()
    {
        return $this->hasMany(ScenarioAttempt::class);
    }
}
