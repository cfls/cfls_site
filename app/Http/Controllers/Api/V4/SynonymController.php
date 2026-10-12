<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Controllers\Controller;
use App\Models\Synonym;
use App\Models\VideoTheme;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SynonymController extends Controller
{
    use ApiResponses;

    public function index(): JsonResponse
    {
        $videoThemes = VideoTheme::where('active', true)
            ->has('synonyms')
            ->with(['synonyms' => fn ($q) => $q->select('video_theme_cloudinary_id', 'word')])
            ->orderBy('title')
            ->select('id', 'title')
            ->get();

        $data = $videoThemes
            ->groupBy(fn (VideoTheme $vt) => $vt->display_title)
            ->map(fn ($group, $displayTitle) => [
                'video_theme_cloudinary_id' => $group->first()->id,
                'word'                      => $displayTitle,
                'synonyms'                  => $group
                    ->flatMap(fn (VideoTheme $vt) => $vt->synonyms->pluck('word'))
                    ->unique()
                    ->sort()
                    ->values(),
            ])
            ->values();

        return $this->ok('OK', $data);
    }

    public function show(int $id): JsonResponse
    {
        $videoTheme = VideoTheme::where('id', $id)
            ->where('active', true)
            ->select('id', 'title')
            ->first();

        if (! $videoTheme) {
            return $this->error('Not found', 404);
        }

        $displayTitle = $videoTheme->display_title;

        $variants = VideoTheme::where('active', true)
            ->where(function ($q) use ($displayTitle) {
                $q->where('title', $displayTitle)
                  ->orWhere('title', 'LIKE', $displayTitle . ' (%');
            })
            ->with(['synonyms' => fn ($q) => $q->select('video_theme_cloudinary_id', 'word')])
            ->select('id', 'title')
            ->get()
            ->filter(fn (VideoTheme $vt) => $vt->display_title === $displayTitle);

        $synonyms = $variants
            ->flatMap(fn (VideoTheme $vt) => $vt->synonyms->pluck('word'))
            ->unique()
            ->sort()
            ->values();

        return $this->ok('OK', [
            'video_theme_cloudinary_id' => $id,
            'word'                      => $displayTitle,
            'synonyms'                  => $synonyms,
        ]);
    }

    public function sync(Request $request): JsonResponse
    {
        $limit   = min((int) $request->query('limit', 500), 1000);
        $desde   = $request->filled('desde') ? $request->date('desde') : null;
        $desdeId = (int) $request->query('desde_id', 0);

        $query = Synonym::query()->select('id', 'video_theme_cloudinary_id', 'word', 'updated_at');

        if ($desde) {
            $query->where(function ($q) use ($desde, $desdeId) {
                $q->where('updated_at', '>', $desde)
                  ->orWhere(function ($q) use ($desde, $desdeId) {
                      $q->where('updated_at', $desde)->where('id', '>', $desdeId);
                  });
            });
        }

        $total = $query->count();
        $items = $query->orderBy('updated_at')->orderBy('id')->limit($limit)->get();

        return response()->json([
            'data'          => $items,
            'total'         => $total,
            'has_more'      => $total > $limit,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }
}
