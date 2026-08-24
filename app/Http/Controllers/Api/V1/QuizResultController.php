<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QuizResultResource;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = QuizResult::create($request->all());

        return new QuizResultResource($result);
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {

        $results = QuizResult::where('user_id', $userId)
            ->get();

        return response()->json([
            'data' => $results
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuizResult $quizResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuizResult $quizResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuizResult $quizResult)
    {
        //
    }

    public function participationDays($userId)
    {
        return response()->json([
            'data' => QuizResult::participationDays($userId)
        ]);
    }

    public function daily($userId)
    {
        return response()->json([
            'data' => QuizResult::dailyPoints($userId)
        ]);
    }

    public function total($userId)
    {
        return response()->json([
            'total' => QuizResult::totalPoints($userId)
        ]);
    }

    public function rankingDaily()
    {
        return response()->json([
            'data' => QuizResult::dailyRanking()
        ]);
    }

    public function rankingTotal()
    {
        return response()->json([
            'data' => QuizResult::totalRanking()
        ]);
    }

    public function check($userId,$slug,$slug_theme,$type)
    {
        $results = QuizResult::where([
            'user_id'  => $userId,
            'syllabus' => $slug,
            'theme'    => $slug_theme,
            'type'     => $type,
        ])->get();

        return response()->json([
            'data' => $results,
            'exists' => $results->isNotEmpty(),
        ]);

    }

    public function getQuizResultForTopic($userId, $slug, $type)
    {
        $count = QuizResult::getQuizResultForTopic($userId, $slug, $type);

        return response()->json([
            'data' => [
                'count' => $count,
            ],
        ]);
    }

    public function sync($userId, Request $request)
    {
        $limit   = min((int) $request->query('limit', 500), 1000);
        $desde   = $request->filled('desde') ? $request->date('desde') : null;
        $desdeId = (int) $request->query('desde_id', 0);

        $query = QuizResult::where('user_id', $userId);

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
            'id', 'user_id', 'syllabus', 'theme', 'theme_variant', 'type', 'score', 'played_at', 'updated_at',
        ]);

        return response()->json([
            'data'          => $items,
            'total'         => $total,
            'has_more'      => $total > $limit,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }

    public function batch(Request $request)
    {
        $results = $request->input('results', []);
        $saved   = 0;

        foreach ($results as $item) {
            $exists = QuizResult::where([
                'user_id'       => $item['user_id'],
                'syllabus'      => $item['syllabus'],
                'theme'         => $item['theme'],
                'theme_variant' => $item['theme_variant'] ?? 'principal',
                'type'          => $item['type'],
            ])->exists();

            if (! $exists) {
                QuizResult::create([
                    'user_id'       => $item['user_id'],
                    'syllabus'      => $item['syllabus'],
                    'theme'         => $item['theme'],
                    'theme_variant' => $item['theme_variant'] ?? 'principal',
                    'type'          => $item['type'],
                    'score'         => $item['score'] ?? 0,
                    'played_at'     => $item['played_at'] ?? now()->toDateString(),
                ]);
                $saved++;
            }
        }

        return response()->json(['saved' => $saved, 'total' => count($results)]);
    }

}
