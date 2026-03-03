<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_id',
        'choice_text',
        'consequence',
        'safety_score',
        'is_recommended',
        'ai_explanation',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_recommended' => 'boolean',
        ];
    }

    public function scenario()
    {
        return $this->belongsTo(Scenario::class);
    }
}
