<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\Word;
use Illuminate\Http\Request;

class WordController
{
    public function sync(Request $request)
    {
        $limit   = min((int) $request->query('limit', 500), 1000);
        $desde   = $request->filled('desde') ? $request->date('desde') : null;
        $desdeId = (int) $request->query('desde_id', 0);

        $query = Word::query();

        if ($desde) {
            $query->where(function ($q) use ($desde, $desdeId) {
                $q->where('updated_at', '>', $desde)
                  ->orWhere(function ($q) use ($desde, $desdeId) {
                      $q->where('updated_at', $desde)
                        ->where('id', '>', $desdeId);
                  });
            });
        }

        $total = $query->count();
        $items = $query->orderBy('updated_at')->orderBy('id')->limit($limit)->get([
            'id', 'name', 'video_theme_cloudinary_id', 'syllabu_id', 'theme_id', 'active', 'updated_at',
        ]);

        return response()->json([
            'data'          => $items,
            'total'         => $total,
            'has_more'      => $total > $limit,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }
}