<?php

namespace App\Http\Controllers;

use App\Models\Syllabu;
use App\Models\Theme;
use App\Models\VideoCheck;
use App\Models\VideoTheme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VideoCheckController extends Controller
{
    public function __construct()
    {
        abort_if(! auth()->check(), 403);
        abort_if(auth()->user()->role !== 'admin', 403);
    }

    public function index(Request $request)
    {
        $grupo = $request->get('grupo', 'descentrado');
        if (! array_key_exists($grupo, VideoCheck::GRUPOS)) {
            $grupo = 'descentrado';
        }

        $query = VideoCheck::with(['videoTheme.themes', 'videoTheme.syllabus'])
            ->where('grupo', $grupo);

        if ($q = trim((string) $request->get('q'))) {
            $matchIds = VideoTheme::where('title', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->pluck('id');
            $query->where(function ($sub) use ($q, $matchIds) {
                $sub->where('public_id', 'like', "%{$q}%")
                    ->orWhereIn('video_theme_cloudinary_id', $matchIds);
            });
        }

        if ($themeId = $request->get('theme_id')) {
            $ids = VideoTheme::where('theme_id', $themeId)->pluck('id');
            $query->whereIn('video_theme_cloudinary_id', $ids);
        }

        $syllabusId = $request->get('syllabu_id');
        if ($syllabusId) {
            $ids = VideoTheme::where('syllabu_id', $syllabusId)->pluck('id');
            $query->whereIn('video_theme_cloudinary_id', $ids);
        }

        if (in_array($request->get('revisado'), ['0', '1'], true)) {
            $query->where('revisado', (bool) $request->get('revisado'));
        }

        $orden = $request->get('orden', 'desviacion');
        $query = match ($orden) {
            'nombre'   => $query->orderBy('public_id'),
            'reciente' => $query->orderByDesc('updated_at'),
            default    => $query->orderByDesc('desviacion'),
        };

        $totales = VideoCheck::selectRaw('grupo, count(*) as cnt')
            ->groupBy('grupo')
            ->pluck('cnt', 'grupo');

        return view('videos.checkvideo', [
            'videos'   => $query->paginate(48)->withQueryString(),
            'grupo'    => $grupo,
            'grupos'   => VideoCheck::GRUPOS,
            'totales'  => $totales,
            'total'    => $totales->sum(),
            'temas'    => $syllabusId
                ? Theme::where('syllabu_id', $syllabusId)->orderBy('title')->get(['id', 'title'])
                : Theme::orderBy('title')->get(['id', 'title']),
            'syllabus' => Syllabu::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function marcar(Request $request, VideoCheck $video)
    {
        $data = $request->validate([
            'grupo' => ['required', Rule::in(array_keys(VideoCheck::GRUPOS))],
        ]);

        $video->update(['grupo' => $data['grupo'], 'revisado' => true]);

        $title = $video->videoTheme?->title ?? $video->public_id;

        return back()->with('status', "«{$title}» déplacé vers " . VideoCheck::GRUPOS[$data['grupo']]);
    }

    public function exportar(Request $request)
    {
        $grupo = $request->get('grupo');

        return response()->streamDownload(function () use ($grupo) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'titulo', 'tema', 'url', 'grupo', 'desviacion', 'revisado']);

            VideoCheck::with(['videoTheme.themes'])
                ->when($grupo, fn ($q) => $q->where('grupo', $grupo))
                ->orderBy('public_id')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        $vt = $r->videoTheme;
                        fputcsv($out, [
                            $r->id,
                            $vt?->title ?? '',
                            $vt?->themes?->title ?? '',
                            $vt?->url ?? '',
                            $r->grupo,
                            $r->desviacion,
                            $r->revisado ? 1 : 0,
                        ]);
                    }
                });

            fclose($out);
        }, 'cadrage-' . ($grupo ?: 'tous') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
