<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpellingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'spelling',
            'id' => $this->id,
            'attributes' => [
                "word" => $this->word,
                "active" => $this->active,
                'difficulty' => $this->difficulty, // ✅
                "created_at" => $this->created_at,
                "updated_at" => $this->updated_at,
            ]
        ];
    }
}
