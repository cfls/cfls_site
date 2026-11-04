<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdventVideo extends Model
{
    protected $fillable = [
        'year',
        'day',
        'video_url',
        'is_published',
    ];

    protected $casts = [
        'year' => 'integer',
        'day' => 'integer',
        'is_published' => 'boolean',
    ];

    // Un jour est débloqué dès que l'admin publie sa vidéo
    public function scopeVisible(Builder $query, int $year): Builder
    {
        return $query->where('year', $year)
            ->where('is_published', true);
    }
}
