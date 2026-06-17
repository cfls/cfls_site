<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use Illuminate\Http\JsonResponse;

class ProgressController extends Controller
{
    public function index($userId): JsonResponse
    {
        // 1️⃣ Obtener todos los temas completados por el usuario
        $completedThemes = QuizResult::where('user_id', $userId)
            ->get(['theme', 'syllabus', 'type']); // ej. syllabus = ue1-themes, ue2-themes

        // 2️⃣ Agrupar por syllabus
        $groupedByType = $completedThemes
            ->groupBy('type') // agrupa por columna "Type"
            ->map(function ($themes) {
                $list = $themes->pluck('theme')->toArray();
                return [
                    'completed_themes' => $list,
                    'total_completed' => count($list),
                ];
            });

        // 3️⃣ Retornar JSON bien estructurado
        return response()->json([
            'data' => [
                'progress' => $groupedByType,
            ],
        ]);
    }

    public function show($userId, $slug): JsonResponse
    {


            // Obtener todos los temas completados por el usuario para un tipo específico
            $completedThemes = QuizResult::where('user_id', $userId)
                ->where('syllabus', $slug)
                ->where('type', 'recap')
                ->where('theme', 'final-exam')
                ->get();

            // Agrupar por syllabus
            $groupedBySyllabus = $completedThemes
                ->groupBy('syllabus')
                ->map(function ($themes) {
                    $list = $themes->pluck('theme')->toArray();
                    return [

                        'total_completed' => count($list),
                    ];
                });

            // Retornar JSON bien estructurado
            return response()->json([
                'data' => [
                    'progress' => $groupedBySyllabus,
                ],
            ]);

        }

}
