<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;

// Mismo formato de salida que QuestionResource, para que la app consuma
// questions_2 sin cambios; se separa para poder evolucionar por su cuenta.
class Question2Resource extends QuestionResource
{
    // Tipos cuyas opciones se mezclan para que la respuesta no quede siempre en la misma posición.
    private const SHUFFLED_TYPES = ['choice', 'video-choice', 'match'];

    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (in_array($data['type'], self::SHUFFLED_TYPES, true)
            && is_array($data['options'])
            && array_is_list($data['options'])) {
            shuffle($data['options']);
        }

        return $data;
    }
}
