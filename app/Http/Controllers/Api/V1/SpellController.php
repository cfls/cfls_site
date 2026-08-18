<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\WordsResource;
use App\Models\Spelling;
use App\Models\Syllabu;
use App\Models\Theme;
use App\Models\VideoTheme;
use App\Models\Word;
use Illuminate\Http\Request;

use App\Models\Letter;

class SpellController extends Controller
{
    public function index($syllabu = null, $theme = null)
    {
//        $theme = Theme::where('slug', $theme)->first();
//        $syllabus = Syllabu::where('slug', $syllabu)->first();




//        $words = Word::select('id', 'name', 'video_theme_cloudinary_id')
//            ->where('theme_id', $theme->id)
//            ->where('syllabu_id', $syllabus->id)
//            ->whereActive(true)
//            ->inRandomOrder()
//            ->get();


        $words = Word::select('id', 'name', 'video_theme_cloudinary_id')
            ->whereActive(true)
            ->inRandomOrder()
            ->get();


        return  WordsResource::collection($words);

    }

    public function spell($id)
    {
        $word = Word::findOrFail($id);
        $letters = preg_split('//u', $word->name, -1, PREG_SPLIT_NO_EMPTY);
        $result = [];

        foreach ($letters as $char) {
            $letter = Letter::where('symbol', strtolower($char))->first();
            if ($letter) {
                $result[] = [
                    'symbol' => $char,
                    'image' => asset($letter->image),
                ];
            }
        }

        return response()->json([
            'word' => $word->name,
            'letters' => $result,
        ]);
    }

    public function sync(Request $request)
    {
        $query = Spelling::query();

        if ($request->filled('desde')) {
            $query->where('updated_at', '>', $request->date('desde'));
        }

        $items = $query->orderBy('updated_at')->get([
            'id', 'word', 'difficulty', 'active', 'updated_at',
        ]);

        return response()->json([
            'data'          => $items,
            'servidor_hora' => now()->toIso8601String(),
        ]);
    }
}
