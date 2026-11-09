<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\Question2Resource;
use App\Models\Question2;
use App\Models\Syllabu;
use App\Models\Theme;
use Illuminate\Http\Request;

// API de juegos sobre la tabla questions_2 (independiente de QuizController / questions).
class Quiz2Controller
{
    private const COLUMNS = [
        'questions_2.id',
        'questions_2.theme_id',
        'questions_2.type',
        'questions_2.question_text',
        'questions_2.answer',
        'questions_2.video_id',
        'questions_2.options',
        'questions_2.status',
    ];

    public function index($slug)
    {
        $syllabusId = $this->syllabusId($slug);

        $questions = Question2::with(['video:id,url', 'theme:id,title'])
            ->where('syllabu_id', $syllabusId)
            ->where('status', 1)
            ->inRandomOrder()
            ->limit(25)
            ->get(self::COLUMNS);

        return response()->json([
            'status' => 'success',
            'data' => Question2Resource::collection($questions),
        ]);
    }

    public function show($slug, $theme, Request $request)
    {
        $syllabusId = $this->syllabusId($slug);

        $theme = Theme::where('syllabu_id', $syllabusId)
            ->where('slug', $theme)
            ->firstOrFail();

        $subtheme = $request->input('subtheme') ?: 'principal';

        $query = $this->baseQuery()
            ->where('questions_2.theme_id', $theme->id)
            ->where(function ($q) use ($subtheme) {
                $q->where('vc.type', $subtheme)
                    ->orWhereNull('questions_2.video_id'); // incluye preguntas sin video
            });

        if ($type = $request->input('type')) {
            $query->where('questions_2.type', $type);
        }

        return Question2Resource::collection($query->inRandomOrder()->limit(15)->get());
    }

    public function synthesis($slug, $type)
    {
        $syllabusId = $this->syllabusId($slug);

        $questions = $this->baseQuery()
            ->where('questions_2.syllabu_id', $syllabusId)
            ->where('questions_2.type', $type)
            ->inRandomOrder()
            ->limit(15)
            ->get();

        return Question2Resource::collection($questions);
    }

    public function themes($slug)
    {
        $syllabusId = $this->syllabusId($slug);

        $questions = Question2::with(['video:id,url', 'theme:id,title'])
            ->where('syllabu_id', $syllabusId)
            ->where('status', 1)
            ->inRandomOrder()
            ->limit(20)
            ->get(self::COLUMNS);

        return Question2Resource::collection($questions);
    }

    public function sync(Request $request)
    {
        $limit = min((int) $request->query('limit', 500), 1000);
        $desde = $request->filled('desde') ? $request->date('desde') : null;
        $desdeId = (int) $request->query('desde_id', 0);

        $query = Question2::query();

        if ($desde) {
            // Cursor compuesto (updated_at, id): evita perder registros cuando
            // varios comparten el mismo updated_at en el límite de una página.
            $query->where(function ($q) use ($desde, $desdeId) {
                $q->where('updated_at', '>', $desde)
                    ->orWhere(function ($q) use ($desde, $desdeId) {
                        $q->where('updated_at', $desde)
                            ->where('id', '>', $desdeId);
                    });
            });
        } elseif ($desdeId > 0) {
            // Full sync sin timestamp: paginar solo por id.
            $query->where('id', '>', $desdeId);
        }

        $total = $query->count();

        $items = $query->orderBy('updated_at')->orderBy('id')->limit($limit)->get([
            'id', 'syllabu_id', 'theme_id', 'video_id', 'question_text', 'type', 'options', 'answer', 'status', 'updated_at',
        ]);

        return response()->json([
            'data' => $items,
            'total' => $total,
            'has_more' => $total > $limit,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }

    private function syllabusId(string $slug): int
    {
        $id = Syllabu::where('slug', $slug)->value('id');

        abort_unless($id, 404);

        return $id;
    }

    // Preguntas activas con el tipo de video (subtheme) y relaciones precargadas.
    private function baseQuery()
    {
        return Question2::query()
            ->leftJoin('video_themes_cloudinary as vc', 'vc.id', '=', 'questions_2.video_id')
            ->where('questions_2.status', 1)
            ->with(['video:id,url', 'theme:id,title'])
            ->select([...self::COLUMNS, 'vc.type as subtheme']);
    }
}
