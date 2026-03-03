<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'type',
        'alt_text',
        'sort_order',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}
