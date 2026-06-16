<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuizResult extends Model
{
    protected $fillable = ['user_id','syllabus','theme','slug','score','played_at'];

    protected $casts = [
        'played_at' => 'date',
    ];

    /**
     * Total de puntos acumulados de un usuario.
     */
    public static function totalPoints($userId)
    {
        return self::where('user_id', $userId)->sum('score');
    }

    public static function participationDays($userId)
    {
        return self::where('user_id', $userId)
            ->distinct('played_at')
            ->count('played_at');
    }

    /**
     * Puntos acumulados por día de un usuario.
     */
    public static function dailyPoints($userId)
    {
        return self::selectRaw('played_at, SUM(score) as total_score')
            ->where('user_id', $userId)
            ->groupBy('played_at')
            ->orderBy('played_at', 'asc')
            ->get();
    }

    /**
     * Ranking global diario (top usuarios del día).
     */
    public static function dailyRanking($date = null, $limit = 10)
    {
        $date = $date ?? now()->toDateString();

        return self::selectRaw('user_id, SUM(score) as total_score')
            ->whereDate('played_at', $date)
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->limit($limit)
            ->get();
    }

    /**
     * Ranking global histórico.
     */
    public static function totalRanking($limit = 10)
    {
        return self::selectRaw('user_id, SUM(score) as total_score')
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->limit($limit)
            ->get();
    }

    public static function getQuizResultForTopic($userId, $slug, $type)
    {
        return self::where('user_id', $userId)
            ->where('syllabus', $slug)
            ->where('type', $type)
            ->count('type');
    }
}
