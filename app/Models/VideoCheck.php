<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoCheck extends Model
{
    public const GRUPOS = [
        'regrabar'    => 'Regrabar',
        'descentrado' => 'Descentrado',
        'sin_persona' => 'Sin persona',
        'revisar'     => 'Revisar',
        'ok'          => 'Correcto',
        'error'       => 'Error',
    ];

    protected $fillable = [
        'video_theme_cloudinary_id', 'public_id', 'grupo', 'desviacion',
        'frames_con_persona', 'frames_total', 'duracion', 'revisado',
    ];

    public function videoTheme(): BelongsTo
    {
        return $this->belongsTo(VideoTheme::class, 'video_theme_cloudinary_id');
    }

    protected $casts = [
        'desviacion' => 'float',
        'duracion'   => 'float',
        'revisado'   => 'boolean',
    ];

    public function thumbnail(int $width = 360, string $offset = '50p'): string
    {
        $cloud = config('services.cloudinary.cloud_name');

        return "https://res.cloudinary.com/{$cloud}/video/upload/so_{$offset},c_crop,w_0.85,h_0.85/ar_5:4,c_fill,w_{$width},q_auto,f_jpg/{$this->public_id}.jpg";
    }

    public function videoUrl(): string
    {
        $cloud = config('services.cloudinary.cloud_name');

        return "https://res.cloudinary.com/{$cloud}/video/upload/c_crop,w_0.85,h_0.85/ar_5:4,c_fill,w_720,q_auto/{$this->public_id}.mp4";
    }
}
