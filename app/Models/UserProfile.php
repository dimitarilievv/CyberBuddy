<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school',
        'grade',
        'language',
        'interests',
        'is_colorblind',
        'large_font',
        'dark_mode',
        'avatar',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'is_colorblind' => 'boolean',
            'large_font' => 'boolean',
            'dark_mode' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
