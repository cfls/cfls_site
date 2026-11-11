<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SportSubsection extends Model
{
    protected $table = 'sport_subsections';

    protected $fillable = ['sport_section_id', 'video_number', 'title', 'content', 'url', 'status', 'sort_order'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(SportSection::class, 'sport_section_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(SportVideo::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /** Portada de la subsección: miniatura de su primer vídeo ($this->url). */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->url || ! str_contains($this->url, '/video/upload/')) {
            return null;
        }

        $thumb = preg_replace('#/video/upload/#', '/video/upload/so_1,w_640,h_360,c_fill,q_auto/', $this->url, 1);

        return preg_replace('/\.[a-z0-9]+$/i', '.jpg', $thumb);
    }
}
