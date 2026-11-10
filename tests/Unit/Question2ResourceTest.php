<?php

use App\Http\Resources\V1\Question2Resource;
use App\Models\Question2;
use Illuminate\Http\Request;

uses(Tests\TestCase::class);

// Pregunta en memoria (sin base de datos) con relaciones vacías para no disparar consultas.
function makeQuestion2(string $type, array $options, ?string $answer = null): Question2
{
    $q = new Question2(['type' => $type, 'options' => $options, 'answer' => $answer, 'question_text' => 'Q']);
    $q->setRelation('video', null);
    $q->setRelation('theme', null);

    return $q;
}

// Repite el render hasta ver un orden distinto al original (4 opciones: 1/24 de salir igual cada vez).
function seesDifferentOrder(Question2 $q, array $original, callable $map): bool
{
    for ($i = 0; $i < 50; $i++) {
        $options = (new Question2Resource($q))->toArray(new Request())['options'];
        if (array_map($map, $options) !== $original) {
            return true;
        }
    }

    return false;
}

it('mezcla las opciones de choice y la respuesta sigue estando entre ellas', function () {
    $options = ['Blesser', 'Épouse-femme', 'Ciel', 'Faire les courses'];
    $q = makeQuestion2('choice', $options, 'Épouse-femme');

    $data = (new Question2Resource($q))->toArray(new Request());

    expect($data['options'])->toEqualCanonicalizing($options)
        ->and($data['options'])->toContain($data['answer'])
        ->and(seesDifferentOrder($q, $options, fn ($o) => $o))->toBeTrue();
});

it('mezcla video-choice sin separar value y video, y la respuesta sigue entre los value', function () {
    $options = [
        ['value' => 'information', 'video' => 'v1.mp4'],
        ['value' => 'épouse-femme', 'video' => 'v2.mp4'],
        ['value' => 'bonne nuit', 'video' => 'v3.mp4'],
        ['value' => 'merci', 'video' => 'v4.mp4'],
    ];
    $q = makeQuestion2('video-choice', $options, 'épouse-femme');

    $data = (new Question2Resource($q))->toArray(new Request());

    expect($data['options'])->toEqualCanonicalizing($options)
        ->and(array_column($data['options'], 'value'))->toContain($data['answer'])
        ->and(seesDifferentOrder($q, array_column($options, 'value'), fn ($o) => $o['value']))->toBeTrue();
});

it('mezcla match sin romper las parejas word/video', function () {
    $options = [
        ['word' => 'Épouse-femme', 'video' => 'a.mp4'],
        ['word' => 'Époux-mari', 'video' => 'b.mp4'],
        ['word' => 'Être capable', 'video' => 'c.mp4'],
        ['word' => 'Être incapable', 'video' => 'd.mp4'],
    ];
    $q = makeQuestion2('match', $options);

    $data = (new Question2Resource($q))->toArray(new Request());

    // Cada pareja llega intacta: mismo conjunto de objetos {word, video}.
    expect($data['options'])->toEqualCanonicalizing($options)
        ->and(seesDifferentOrder($q, array_column($options, 'word'), fn ($o) => $o['word']))->toBeTrue();
});

it('no mezcla los tipos fuera de la lista', function () {
    $options = ['Oui', 'Non'];
    $q = makeQuestion2('yes-no', $options);

    for ($i = 0; $i < 20; $i++) {
        expect((new Question2Resource($q))->toArray(new Request())['options'])->toBe($options);
    }
});
