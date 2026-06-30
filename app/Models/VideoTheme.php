<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTheme extends Model
{
    protected $table = 'video_themes_cloudinary';

    const TYPES = [
        'principal'           => 'Principal',
        'pour_en_savoir_plus' => 'Pour en savoir plus',
    ];

    public function syllabus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Syllabu::class, 'syllabu_id');
    }

    // Scopes útiles
    public function scopePrincipal($query)
    {
        return $query->where('type', 'principal');
    }

    public function scopeAnnexes($query)
    {
        return $query->where('type', '!=', 'principal');
    }


    public function themes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
