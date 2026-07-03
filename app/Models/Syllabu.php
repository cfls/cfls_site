<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Syllabu extends Model
{
    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class, 'syllabu_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(VideoTheme::class, 'syllabu_id');
    }

    public function getFormattedTitleAttribute()
    {
        return strtoupper(Str::of($this->title)->replace(['themes', 'theme'], 'par thèmes'));
    }
}
