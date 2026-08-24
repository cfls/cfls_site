<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoQuizItem extends Model
{
    protected $fillable = [

        'title',
        'video_theme_cloudinary_id',
        'question',
        'options',
        'correct_answer',
        'active',
        'syllabu_id',
        'theme_id',

    ];

    public function videoThemeCloudinary(): BelongsTo
    {
        return $this->belongsTo(VideoTheme::class);
    }


}
