<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\SpellingResource;
use App\Models\Spelling;

class SpellingController
{

     public function index()
     {
         $spellings = Spelling::whereActive(1)
             ->inRandomOrder()
             ->get();
         return SpellingResource::collection($spellings);

     }
}