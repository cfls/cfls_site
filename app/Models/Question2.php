<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question2 extends Model
{
    protected $table = 'questions_2';

    protected $fillable = [
        'syllabu_id',
        'theme_id',
        'video_id',
        'question_text',
        'type',
        'options',
        'answer',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabu::class, 'syllabu_id');
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(VideoTheme::class, 'video_id');
    }

    public function getVideoThumbnail(string $videoUrl, int $second = 2, int $width = 320, int $height = 180): string
    {
        return preg_replace(
            ['/\/upload\//', '/\.mp4$/'],
            ["/upload/so_{$second},w_{$width},h_{$height},c_fill/", '.jpg'],
            $videoUrl
        );
    }

    public function getVideoThumbnail2($id): string
    {
        $video = VideoTheme::where('id', $id)->first();

        return $video ? $this->getVideoThumbnail($video->url) : '';
    }
}
