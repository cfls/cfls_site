<?php

use App\Models\Question2;
use App\Models\Syllabu;
use App\Models\Theme;

// Syllabus con $count preguntas choice activas, sin video. Devuelve [syllabus, theme, ids].
function seedQuiz2(int $count): array
{
    $syllabu = new Syllabu();
    $syllabu->title = 'Quiz2 syllabu';
    $syllabu->slug = 'quiz2-' . uniqid();
    $syllabu->save();

    $theme = Theme::create([
        'title' => 'Quiz2 theme',
        'slug' => 'quiz2-theme-' . uniqid(),
        'syllabu_id' => $syllabu->id,
    ]);

    $ids = collect(range(1, $count))->map(fn ($i) => Question2::create([
        'syllabu_id' => $syllabu->id,
        'theme_id' => $theme->id,
        'question_text' => "Q$i",
        'type' => 'choice',
        'options' => ['A', 'B', 'C', 'D'],
        'answer' => 'A',
        'status' => 1,
    ])->id)->all();

    return [$syllabu, $theme, $ids];
}

function quiz2Urls(Syllabu $syllabu, Theme $theme): array
{
    return [
        "/api/v1/questions2/{$syllabu->slug}",
        "/api/v1/questions2/all/{$syllabu->slug}",
        "/api/v1/questions2/{$syllabu->slug}/{$theme->slug}",
        "/api/v1/synthesis2/{$syllabu->slug}/choice",
    ];
}

it('devuelve 15 preguntas y sin meta cuando no hay exclude', function () {
    [$syllabu, $theme] = seedQuiz2(40);

    foreach (quiz2Urls($syllabu, $theme) as $url) {
        $this->getJson($url)
            ->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJsonMissingPath('meta');
    }
});

it('con exclude solo devuelve preguntas no vistas mientras alcancen', function () {
    [$syllabu, $theme, $ids] = seedQuiz2(40);
    $seen = array_slice($ids, 0, 25); // quedan 15 sin ver

    foreach (quiz2Urls($syllabu, $theme) as $url) {
        $response = $this->getJson($url . '?exclude=' . implode(',', $seen))
            ->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.reset', false);

        expect(collect($response->json('data'))->pluck('id')->intersect($seen))->toBeEmpty();
    }
});

it('completa con vistas y marca reset cuando no alcanzan las no vistas', function () {
    [$syllabu, $theme, $ids] = seedQuiz2(20);
    $seen = array_slice($ids, 0, 15); // quedan 5 sin ver
    $unseen = array_slice($ids, 15);

    foreach (quiz2Urls($syllabu, $theme) as $url) {
        $returned = collect($this->getJson($url . '?exclude=' . implode(',', $seen))
            ->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.reset', true)
            ->json('data'))->pluck('id');

        expect($returned->unique())->toHaveCount(15)
            ->and($returned->intersect($unseen)->values()->all())->toEqualCanonicalizing($unseen);
    }
});

it('recorre todas las preguntas a lo largo de las partidas sin repetir antes del reset', function () {
    [$syllabu] = seedQuiz2(98);
    $seen = [];

    // 98 / 15 => 6 partidas completas sin repetir; la 7.ª trae las 8 restantes y hace reset.
    for ($game = 1; $game <= 6; $game++) {
        $data = $this->getJson("/api/v1/questions2/{$syllabu->slug}?exclude=" . implode(',', $seen))
            ->assertJsonPath('meta.reset', $game === 1 ? null : false)
            ->json('data');
        $new = collect($data)->pluck('id')->all();

        expect(array_intersect($new, $seen))->toBeEmpty();
        $seen = array_merge($seen, $new);
    }

    $last = $this->getJson("/api/v1/questions2/{$syllabu->slug}?exclude=" . implode(',', $seen))
        ->assertJsonPath('meta.reset', true)
        ->json('data');

    expect(array_unique(array_merge($seen, collect($last)->pluck('id')->all())))->toHaveCount(98);
});

it('ignora valores no numéricos en exclude', function () {
    [$syllabu] = seedQuiz2(20);

    $this->getJson("/api/v1/questions2/{$syllabu->slug}?exclude=abc,-3,,0,1.5")
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonMissingPath('meta');
});

it('con más de 500 ids en exclude conserva solo los últimos 500', function () {
    [$syllabu, , $ids] = seedQuiz2(20);
    // 600 IDs inexistentes primero y luego 10 reales: si se conservan los últimos 500, los 10 reales se excluyen.
    $exclude = array_merge(range(900001, 900600), array_slice($ids, 0, 10));

    $returned = collect($this->getJson("/api/v1/questions2/{$syllabu->slug}?exclude=" . implode(',', $exclude))
        ->assertOk()
        ->assertJsonPath('meta.reset', true)
        ->json('data'))->pluck('id');

    expect($returned->intersect(array_slice($ids, 10))->count())->toBe(10);
});
