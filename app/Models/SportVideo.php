<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SportVideo extends Model
{
    protected $table = 'sport_videos';

    protected $fillable = ['sport_subsection_id', 'title', 'public_id', 'url', 'status', 'sort_order'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function subsection(): BelongsTo
    {
        return $this->belongsTo(SportSubsection::class, 'sport_subsection_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /** Miniatura que genera Cloudinary a partir del vídeo (fotograma del segundo 1). */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->url || ! str_contains($this->url, '/video/upload/')) {
            return null;
        }

        $thumb = preg_replace('#/video/upload/#', '/video/upload/so_1,w_640,h_360,c_fill,q_auto/', $this->url, 1);

        return preg_replace('/\.[a-z0-9]+$/i', '.jpg', $thumb);
    }
}
