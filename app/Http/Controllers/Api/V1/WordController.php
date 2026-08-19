<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\Word;
use Illuminate\Http\Request;

class WordController
{
    public function sync(Request $request)
    {
        $query = Word::query();

        if ($request->filled('desde')) {
            $query->where('updated_at', '>', $request->date('desde'));
        }

        $items = $query->orderBy('updated_at')->get([
            'id','name', 'video_theme_cloudinary_id', 'syllabu_id', 'theme_id', 'active', 'updated_at',
        ]);

        return response()->json([
            'data' => $items,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }
}