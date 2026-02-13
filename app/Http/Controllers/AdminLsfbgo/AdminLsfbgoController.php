<?php

namespace App\Http\Controllers\AdminLsfbgo;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Syllabu;
use App\Models\Theme;
use App\Models\VideoTheme;
use Cloudinary\Asset\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLsfbgoController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('lsfbgo.index');
    }


    public function createQuestion(Request $request)
    {
        // 🔹 Limpiar campos "null" que vienen como string
        $input = $request->all();

        foreach (['question_text', 'video_url'] as $field) {
            if (isset($input[$field]) && $input[$field] === 'null') {
                $input[$field] = null;
            }
        }

        // 🔹 Reemplazar el request con datos limpios
        $request->merge($input);

        try {
            // 🔹 Validación base
            $rules = [
                'syllabu_id'   => 'required|exists:syllabus,id',
                'theme_id'     => 'required|exists:themes,id',
                'type'         => 'required|in:text,choice,yes-no,video-choice',
                'video_url'    => 'nullable|url|max:500',
                'question_text'=> 'nullable|string|max:500',
                'answer'       => 'required|string|max:255',
            ];

            $messages = [
                'syllabu_id.required' => 'Le programme est requis',
                'theme_id.required'   => 'Le thème est requis',
                'type.required'       => 'Le type de question est requis',
                'video_url.url'       => 'L\'URL de la vidéo doit être valide',
                'answer.required'     => 'La réponse est obligatoire',
            ];

            // 🔹 Validación según tipo
            $type = $request->input('type');

            if ($type === 'video-choice') {
                // Para video-choice: validar array de objetos
                $rules['options'] = 'required|array|min:2|max:4';
                $rules['options.*.value'] = 'required|string|max:255';
                $rules['options.*.video'] = 'required|url|max:500';

                $messages['options.required'] = 'Au moins 2 options sont requises';
                $messages['options.min'] = 'Au moins 2 options sont requises';
                $messages['options.max'] = 'Maximum 4 options autorisées';
                $messages['options.*.value.required'] = 'Le texte de l\'option est obligatoire';
                $messages['options.*.video.required'] = 'L\'URL de la vidéo d\'option est obligatoire';
                $messages['options.*.video.url'] = 'L\'URL de la vidéo doit être valide';

            } elseif ($type === 'choice') {
                // Para choice: validar array simple de strings
                $rules['options'] = 'required|array|min:2|max:4';
                $rules['options.*'] = 'required|string|max:255';

                $messages['options.required'] = 'Au moins 2 options sont requises';
                $messages['options.min'] = 'Au moins 2 options sont requises';
                $messages['options.max'] = 'Maximum 4 options autorisées';
                $messages['options.*.required'] = 'Le texte de l\'option est obligatoire';
            }

            $validated = $request->validate($rules, $messages);

            // 🔹 Limpiar opciones según el tipo
            $cleanOptions = $this->cleanOptions($validated['options'] ?? [], $validated['type']);

            // 🔹 Obtener video (si existe)
            $video = VideoTheme::where('url', $validated['video_url'])->first();
            $videoId = $video?->id;

            // 🔹 Generar opciones finales según tipo
            $optionsForApi = match ($validated['type']) {
                'choice', 'video-choice' => $cleanOptions,
                'yes-no'                 => ['oui', 'non'],
                default                  => null,
            };

            $questionText = $validated['question_text'] ?? null;

            if ($validated['type'] === 'video-choice') {
                $questionText = 'Choisissez la vidéo qui correspond au mot : ' . $validated['answer'];
            }

            // 🔹 Crear pregunta
            Question::create([
                'syllabu_id'   => $validated['syllabu_id'],
                'theme_id'     => $validated['theme_id'],
                'type'         => $validated['type'],
                'video_id'     => $videoId,
                'question_text'=> $questionText,
                'answer'       => $validated['answer'],
                'options'      => $optionsForApi
                    ? json_encode($optionsForApi, JSON_UNESCAPED_UNICODE)
                    : null,
            ]);

            return redirect()->route('admin-lsfbgo.show-questions', [
                'syllabu_id' => $validated['syllabu_id'],
                'theme_id'   => $validated['theme_id'],
                'type'       => $validated['type']
            ])->with('success', 'Question créée avec succès!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 🔍 Debug errores de validación
            return back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    private function cleanOptions(array $options, string $type): array
    {
        if ($type === 'video-choice') {
            return array_values(array_filter(
                $options,
                fn($opt) =>
                    !empty(trim($opt['value'] ?? '')) &&
                    !empty(trim($opt['video'] ?? ''))
            ));
        }

        if ($type === 'choice') {
            return array_values(array_filter($options, function ($opt) {
                return !empty(trim($opt));
            }));
        }

        return [];
    }

    public function deleteQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
        ], [
            'question_id.required' => 'L\'ID de la question est requis',
            'question_id.exists' => 'La question spécifiée n\'existe pas',
        ]);

        $question = Question::findOrFail($validated['question_id']);
        $question->delete();


        return redirect()->back()->with('success', 'Question supprimée avec succès!');


    }


    public function type($type, Request $request)
    {
        // Obtener los 3 parámetros
        $syllabusId = $request->query('syllabus');
        $themeId = $request->query('theme');



        // Validaciones
        if (!$syllabusId) {
            return redirect()->back()
                ->withErrors(['error' => 'Veuillez sélectionner un programme']);
        }

        if (!$themeId) {
            return redirect()->back()
                ->withErrors(['error' => 'Veuillez sélectionner un thème']);
        }

        // Obtener los objetos
        $syllabus = Syllabu::findOrFail($syllabusId);
        $theme = Theme::findOrFail($themeId);


        // Habilitar query log


        $questions = Question::with('video')
            ->where('syllabu_id', $syllabusId)
            ->where('theme_id', $themeId)
            ->where('type', $type)
            ->paginate(20);





        // Retornar vista con los datos
        return view('lsfbgo.'.$type, compact('type', 'syllabus', 'theme', 'questions'));
    }

    public function showQuestions($syllabus_id, $theme_id, $type)
    {



        $syllabus = Syllabu::findOrFail($syllabus_id);
        $theme = Theme::findOrFail($theme_id);

        // Construir la query base
        $query = Question::where('syllabu_id', $syllabus_id)
            ->where('theme_id', $theme_id)
            ->where('type', $type)
            ->with('video');





        // Aplicar búsqueda si existe
        if ($search = request('search')) {
            $searchColumn = $type === 'yes-no' ? 'question_text' : 'answer';

            $query->where($searchColumn, 'LIKE', "%{$search}%");
        }

        // Paginar resultados
        $questions = $query->orderBy('id', 'asc')->paginate(15);


        return view('lsfbgo.'.$type, compact('type', 'syllabus', 'theme', 'questions'));
    }

    public function updateQuestion(Request $request, Question $question)
    {
        // Validación base
        $rules = [
            'answer' => 'required|string|max:255',
            'question_text' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
        ];

        $messages = [
            'answer.required' => 'La réponse est obligatoire',
        ];


        // Validación según tipo de opciones
        if ($question->type === 'video-choice') {
            // Para opciones con video
            $rules['options'] = 'nullable|array';
            $rules['options.*.value'] = 'required_with:options|string|max:255';
            $rules['options.*.video'] = 'required_with:options|url|max:500';
            $messages['options.*.value.required_with'] = 'Le texte de l\'option est obligatoire';
            $messages['options.*.video.required_with'] = 'L\'URL de la vidéo d\'option est obligatoire';
        } elseif ($question->type === 'choice') {
            // Para opciones simples (texto)
            $rules['options'] = 'nullable|array';
            $rules['options.*'] = 'required|string|max:255';
            $messages['options.*.required'] = 'Le texte de l\'option est obligatoire';
        }

        $validated = $request->validate($rules, $messages);



        // Actualizar campos básicos
        $question->answer = $validated['answer'];

        if (isset($validated['question_text'])) {
            $question->question_text = $validated['question_text'];
        }

        // Actualizar URL del video principal
        if (isset($validated['video_url']) && !empty($validated['video_url'])) {
            if ($question->video) {
                $question->video->url = $validated['video_url'];
                $question->video->save();
            }
        }

        // Actualizar opciones
        if (isset($validated['options']) && is_array($validated['options'])) {
            if ($question->type === 'video-choice') {
                // Para video-choice: guardar array de objetos
                $cleanOptions = array_values(array_filter(
                    $validated['options'],
                    fn($opt) => !empty(trim($opt['value'] ?? ''))
                ));
            } else {
                // Para choice: guardar array simple
                $cleanOptions = array_values(array_filter(
                    $validated['options'],
                    fn($opt) => !empty(trim($opt))
                ));
            }

            // Asignar directamente si tienes cast 'array' en el modelo
            $question->options = json_encode($cleanOptions, JSON_UNESCAPED_UNICODE);

        }


        $question->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Question mise à jour avec succès!'
            ]);
        }

        return redirect()->back()->with('success', 'Question mise à jour avec succès!');
    }


}