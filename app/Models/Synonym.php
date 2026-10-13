<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Synonym extends Model
{
    use SoftDeletes;

    protected $fillable = ['video_theme_cloudinary_id', 'word'];

    public function videoTheme(): BelongsTo
    {
        return $this->belongsTo(VideoTheme::class, 'video_theme_cloudinary_id');
    }
}
