<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\VideoTheme;
use Illuminate\Http\Request;

class VideoController {

    public function sync(Request $request)
    {
        $query = VideoTheme::query();

        if ($request->filled('desde')) {
            $query->where('updated_at', '>', $request->date('desde'));
        }

        $items = $query->orderBy('updated_at')->get([
            'id', 'title', 'slug', 'type', 'theme_id', 'syllabu_id', 'url', 'active', 'updated_at',
        ]);

        return response()->json([
            'data'          => $items,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }

}