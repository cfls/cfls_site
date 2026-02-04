<?php

namespace App\Http\Controllers\Api\V1;



use App\Http\Resources\V1\VerifyCodeResource;
use App\Models\Syllabu;
use App\Models\User;
use App\Models\VerifyCode;

class VerifyCodeController
{
    public function index(User $user, $theme = null)
    {
        $query = VerifyCode::where('user_id', $user->id);

        // Solo filtrar por theme si se proporciona
        if ($theme !== null) {
            $query->where('theme', $theme);
        }

        $verifyCodes = $query->get();

        return VerifyCodeResource::collection($verifyCodes);
    }
}