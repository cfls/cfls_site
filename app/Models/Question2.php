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
}
