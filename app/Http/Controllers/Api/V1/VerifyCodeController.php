<?php

namespace App\Http\Controllers\Api\V1;



use App\Http\Resources\V1\VerifyCodeResource;
use App\Models\Syllabu;
use App\Models\User;
use App\Models\VerifyCode;
use Illuminate\Http\Request;

class VerifyCodeController
{
    public function index(User $user, $theme = null)
    {
        $query = VerifyCode::where('user_id', $user->id)
            ->where('active', 1);

        // Solo filtrar por theme si se proporciona
        if ($theme !== null) {
            $query->where('theme', $theme);
        }
  

        $verifyCodes = $query->get();

        return VerifyCodeResource::collection($verifyCodes);
    }


   public function store(Request $request)
    {
        $user = $request->user();
        $code = $request->input('code');
        $theme = $request->input('theme');

         $verifyCode = VerifyCode::where('code', $code)
            ->where('active', 0)
            ->first();

        if (!$verifyCode) {
            return response()->json(['message' => 'Code de livre invalide.'], 400);
        }

         $verifyCode = VerifyCode::updateOrCreate(
            ['code' => $code],
            [
                'user_id'=> $user->id,
                'active' => 1,
                'theme'  => $theme,
            ]
        );

        return new VerifyCodeResource($verifyCode);
    }
}