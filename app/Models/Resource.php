<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'type',
        'url',
        'file_path',
        'file_size',
        'sort_order',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
