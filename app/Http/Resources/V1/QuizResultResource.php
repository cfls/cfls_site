<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'results',
            'id' => $this->id,
            'attributes' => [
                'user_id'   => $this->user_id,
                'syllabus'  => $this->syllabus,
                'theme'     => $this->theme,
                'type'      => $this->type,
                'theme_variant' => $this->theme_variant,
                'score'     => $this->score,
                'played_at' => $this->played_at?->format('Y-m-d'), // formato limpio
            ]
        ];
    }
}
