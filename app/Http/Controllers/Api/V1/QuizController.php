<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\QuestionResource;
use App\Models\Question;
use App\Models\Syllabu;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QuizController
{
    public function index($slug = null)
    {
        $limit = 25;



        $syllabu = Syllabu::where('slug', $slug)->firstOrFail();

// ✅ Usar directamente $syllabu->id
        $totalQuestions = Question::where('syllabu_id', $syllabu->id)->count();

        $maxOffset = max(0, $totalQuestions - $limit);
        $randomOffset = rand(0, $maxOffset);

        $questions = Question::with('video')
            ->where('syllabu_id', $syllabu->id) // ✅ Usar directamente
            ->whereStatus(1) // ✅ Filtrar solo preguntas activas           
            ->offset($randomOffset)
            ->limit($limit)
            ->get()
            ->shuffle();


        return response()->json([
            'status' => 'success',
            'data'   => QuestionResource::collection($questions),
        ]);
    }

    public function show($slug, $theme, Request $request)
    {
        $syllabusId = Syllabu::where('slug', $slug)->value('id');

        if (!$syllabusId) {
            abort(404);
        }

        $theme = Theme::where('syllabu_id', $syllabusId)
            ->where('slug', $theme)
            ->firstOrFail();

        $theme->mainVideos = $theme->mainVideos()->get();
        foreach ($theme->mainVideos as $mainVideo) {
            $mainVideo->videos = $mainVideo->videos()->get();
        }

        $theme->annexes = $theme->annexes()->get();
        foreach ($theme->annexes as $annex) {
            $annex->videos = $annex->videos()->get();
        }

        $type = $request->input('type');
        $subtheme = $request->input('subtheme');
        $requestedSubtheme = $subtheme ?: 'principal';

        $query = Question::query()
            ->leftJoin('video_themes_cloudinary as vc', 'vc.id', '=', 'questions.video_id')
            ->where('questions.theme_id', $theme->id)
            ->where('questions.status', 1)
            ->where(function ($q) use ($requestedSubtheme) {
                $q->where('vc.type', $requestedSubtheme)
                    ->orWhereNull('questions.video_id'); // incluye preguntas sin video
            });

        if ($type) {
            $query->where('questions.type', $type);
        }

        if ($query->count() === 0) {
            return response()->json(['data' => []]);
        }

        $questions = $query
            ->select([
                'questions.id',
                'questions.theme_id',
                'questions.type',
                'vc.type as subtheme',
                'questions.question_text',
                'questions.answer',
                'questions.video_id',
                'questions.options',
                'questions.status',
            ])
            ->inRandomOrder()
            ->limit(15)
            ->get();

        return QuestionResource::collection($questions);
    }

    public function themes($slug)
    {



        $syllabusId = Syllabu::where('slug', $slug)->value('id');



        if (!$syllabusId) {
            abort(404);
        }



        $query = Question::where('syllabu_id', $syllabusId)->whereStatus(1);

        if ($query->count() === 0) {
            return response()->json(['data' => []]);
        }

        $questions = $query
            ->with(['video:id,title,url'])
            ->select(['id', 'theme_id', 'type', 'question_text', 'answer', 'video_id', 'options', 'status'])
            ->inRandomOrder() // ✅ Aleatorio real a nivel DB
            ->limit(20)
            ->get();

        return QuestionResource::collection($questions);
    }

    public function setting(Request $request)
    {
        $syllabus = Syllabu::all();
        $syllabuId = $request->query('syllabu_id', $syllabus->first()?->id ?? 1);

        // ✅ Filtrar themes solo del syllabus seleccionado
        $themes = Theme::where('syllabu_id', $syllabuId)->get();
        $themeId = $request->query('theme_id', $themes->first()?->id ?? 1);

        $questions = Question::with(['video', 'theme', 'syllabus'])
            ->where('syllabu_id', $syllabuId)
            ->where('theme_id', $themeId)
            ->where('type', 'text')
            ->orderBy('answer')
            ->get();

        return view('lsfbgo.questions', compact(
            'syllabus',
            'themes',
            'syllabuId',
            'themeId',
            'questions'
        ));
    }

    public function updateAnswer(Request $request, $id)
    {
        // Validar solo el campo answer
        $validated = $request->validate([
            'answer' => 'required|string|max:255',
        ]);

        try {
            // Buscar la pregunta
            $question = Question::findOrFail($id);

            // Actualizar solo answer
            $question->answer = $validated['answer'];
            $question->save();

            return response()->json([
                'success' => true,
                'message' => 'Réponse mise à jour avec succès',
                'question' => $question
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
