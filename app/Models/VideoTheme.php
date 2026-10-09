<?php

namespace App\Models;

use Cloudinary\Asset\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoTheme extends Model
{
    protected $table = 'video_themes_cloudinary';

     public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabu::class, 'syllabu_id');
    }

    public function themes(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    public function videos()
    {
        return $this->belongsTo(VideoTheme::class, 'theme_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function synonyms(): HasMany
    {
        return $this->hasMany(Synonym::class, 'video_theme_cloudinary_id');
    }
}
