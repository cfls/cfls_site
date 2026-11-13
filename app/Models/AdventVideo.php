<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    // Un jour est débloqué quand sa vidéo est publiée ET que son heure d'ouverture est passée
    public function scopeVisible(Builder $query, int $year): Builder
    {
        $query->where('year', $year)
            ->where('is_published', true);

        if (config('advent.force')) {
            return $query;
        }

        return $query->where('day', '<=', static::lastUnlockedDay($year));
    }

    // Date/heure d'ouverture d'une case (ex. 3 décembre à 10:00 heure belge)
    public static function unlockAt(int $year, int $day): Carbon
    {
        return Carbon::create($year, 12, $day, config('advent.unlock_hour'), 0, 0, config('advent.timezone'));
    }

    // Dernier jour dont l'heure d'ouverture est passée (0 si aucun)
    public static function lastUnlockedDay(int $year): int
    {
        $now = now(config('advent.timezone'));

        for ($day = 25; $day >= 1; $day--) {
            if ($now->gte(static::unlockAt($year, $day))) {
                return $day;
            }
        }

        return 0;
    }

    public function getUnlockAtAttribute(): Carbon
    {
        return static::unlockAt($this->year, $this->day);
    }
}
