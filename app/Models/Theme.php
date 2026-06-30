<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'syllabu_id',
    ];

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabu::class, 'syllabu_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(VideoTheme::class, 'theme_id')
            ->where('active', 1)
            ->orderBy('title', 'asc');
    }

    // Solo videos principales
    public function mainVideos(): HasMany
    {
        return $this->hasMany(VideoTheme::class)->where('type', 'principal');
    }

    // Solo anexos
    public function annexes(): HasMany
    {
        return $this->hasMany(VideoTheme::class)->where('type', '!=', 'principal');
    }


}
