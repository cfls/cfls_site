<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VideoQuizItemResource;
use App\Models\Syllabu;
use App\Models\Theme;
use App\Models\VideoQuizItem;
use Illuminate\Http\Request;

class VideoQuizItemController extends Controller
{
    public function sync(Request $request)
    {
        $limit   = min((int) $request->query('limit', 500), 1000);
        $desde   = $request->filled('desde') ? $request->date('desde') : null;
        $desdeId = (int) $request->query('desde_id', 0);

        $query = VideoQuizItem::query();

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
            'id', 'title', 'video_theme_cloudinary_id', 'question', 'options',
            'correct_answer', 'active', 'syllabu_id', 'theme_id', 'updated_at',
        ]);

        return response()->json([
            'data'          => $items,
            'total'         => $total,
            'has_more'      => $total > $limit,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }

    public function index($syllabu = null, $theme = null)
    {
        $theme = Theme::where('slug', $theme)->firstOrFail();
        $syllabus = Syllabu::where('slug', $syllabu)->firstOrFail();

        $videos = VideoQuizItem::where('theme_id', $theme->id)
            ->where('syllabu_id', $syllabus->id)
            ->whereActive(true)
            ->inRandomOrder()
            ->get();

        $count = $videos->count();

        return response()->json([
            'count' => $count,
            'videos' => VideoQuizItemResource::collection($videos),
        ]);
    }
}
