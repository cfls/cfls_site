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
        $grupo = $this->grupo($request);
        $syllabusId = $request->get('syllabu_id');
        $query = $this->filtrar($request, $grupo)->with('videoTheme.syllabus');

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

    private function grupo(Request $request): string
    {
        $grupo = $request->get('groupe', 'decentre');

        return array_key_exists($grupo, VideoCheck::GRUPOS) ? $grupo : 'decentre';
    }

    private function filtrar(Request $request, string $grupo)
    {
        $query = VideoCheck::with(['videoTheme.themes'])
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
            'az'       => $query->orderBy('public_id'),
            'za'       => $query->orderByDesc('public_id'),
            'nombre'   => $query->orderBy('public_id'),
            'reciente' => $query->orderByDesc('updated_at'),
            default    => $query->orderByDesc('desviacion'),
        };

        // desempate estable: evita filas repetidas/omitidas al paginar o exportar por bloques
        return $query->orderBy('id');
    }

    public function marcar(Request $request, VideoCheck $video)
    {
        $data = $request->validate([
            'groupe'  => ['nullable', Rule::in(array_keys(VideoCheck::GRUPOS))],
            'mover_a' => ['nullable', Rule::in(array_keys(VideoCheck::GRUPOS))],
        ]);

        $groupe = ($data['groupe'] ?? null) ?: ($data['mover_a'] ?? null);
        abort_if(! $groupe, 422);

        $video->update(['grupo' => $groupe, 'revisado' => true]);

        $title = $video->videoTheme?->title ?? $video->public_id;

        return back()->with('status', "«{$title}» déplacé vers " . VideoCheck::GRUPOS[$groupe]);
    }

    public function exportar(Request $request)
    {
        $grupo = $this->grupo($request);
        $query = $this->filtrar($request, $grupo);

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel lea bien los acentos
            fputcsv($out, ['id', 'titulo', 'tema', 'url', 'grupo', 'desviacion', 'revisado']);

            foreach ($query->lazy(500) as $r) {
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

            fclose($out);
        }, 'cadrage-' . $grupo . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
