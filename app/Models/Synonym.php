<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Synonym extends Model
{
    protected $fillable = ['video_theme_cloudinary_id', 'word'];

    public function videoTheme(): BelongsTo
    {
        return $this->belongsTo(VideoTheme::class, 'video_theme_cloudinary_id');
    }
}
