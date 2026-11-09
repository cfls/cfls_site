<?php

namespace App\Http\Resources\V1;

// Mismo formato de salida que QuestionResource, para que la app consuma
// questions_2 sin cambios; se separa para poder evolucionar por su cuenta.
class Question2Resource extends QuestionResource
{
}
