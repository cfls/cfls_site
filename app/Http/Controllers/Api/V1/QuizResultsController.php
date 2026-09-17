<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizResultsController extends Controller
{

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id)
    {

        // Delete the quiz result with the given ID
        $quizResult = QuizResult::where('user_id', $user_id);

        if (!$quizResult) {
            return response()->json(['message' => 'Quiz result not found'], 404);
        }

        $quizResult->delete();

        return response()->json(['message' => 'Quiz result deleted successfully'], 200);
    }
}
