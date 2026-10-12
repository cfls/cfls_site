<?php

use App\Livewire\VideoModal;
use App\Models\Synonym;
use App\Models\Syllabu;
use App\Models\Theme;
use App\Models\VideoTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

// ──────────────────────────────────────────────
// Helpers
// ──────────────────────────────────────────────

function makeSyllabu(): Syllabu
{
    $s = new Syllabu();
    $s->title = 'Test syllabu';
    $s->slug  = 'test-syllabu-' . uniqid();
    $s->save();
    return $s;
}

function makeTheme(int $syllabId): Theme
{
    return Theme::create([
        'title'      => 'Test theme',
        'slug'       => 'test-theme-' . uniqid(),
        'syllabu_id' => $syllabId,
    ]);
}

function makeVideoTheme(string $title, int $themeId, int $syllabId): VideoTheme
{
    $v = new VideoTheme();
    $v->title      = $title;
    $v->slug       = \Illuminate\Support\Str::slug($title) . '-' . uniqid();
    $v->theme_id   = $themeId;
    $v->syllabu_id = $syllabId;
    $v->code_video = 'https://res.cloudinary.com/dmhdsjmzf/video/upload/v1/test/' . \Illuminate\Support\Str::slug($title) . '.mp4';
    $v->active     = true;
    $v->save();
    return $v;
}

// ──────────────────────────────────────────────
// Model relationship tests
// ──────────────────────────────────────────────

it('VideoTheme hasMany synonyms', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Compréhension']);
    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    $video->refresh();

    expect($video->synonyms)->toHaveCount(2);
    expect($video->synonyms->pluck('word')->sort()->values()->toArray())
        ->toBe(['Compréhension', 'Entendre']);
});

it('Synonym belongsTo VideoTheme', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    $synonym = Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    expect($synonym->videoTheme->id)->toBe($video->id);
});

it('synonyms cascade delete with VideoTheme', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    $video->delete();

    expect(Synonym::count())->toBe(0);
});

// ──────────────────────────────────────────────
// VideoModal component tests
// ──────────────────────────────────────────────

it('modal loads synonyms when opening a word', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Compréhension']);
    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    Livewire::test(VideoModal::class)
        ->call('openByTitle', 'Comprendre')
        ->assertSet('synonyms', ['Compréhension', 'Entendre']);
});

it('modal deduplicates synonyms across grouped variants', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video1  = makeVideoTheme('Aller (1)', $theme->id, $syllabu->id);
    $video2  = makeVideoTheme('Aller (2)', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video1->id, 'word' => 'Partir']);
    Synonym::create(['video_theme_cloudinary_id' => $video1->id, 'word' => 'Marcher']);
    Synonym::create(['video_theme_cloudinary_id' => $video2->id, 'word' => 'Partir']); // duplicado

    Livewire::test(VideoModal::class)
        ->call('openByTitle', 'Aller')
        ->assertSet('synonyms', ['Marcher', 'Partir']);
});

it('modal sets empty synonyms when none exist', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    makeVideoTheme('Bonjour', $theme->id, $syllabu->id);

    Livewire::test(VideoModal::class)
        ->call('openByTitle', 'Bonjour')
        ->assertSet('synonyms', []);
});

it('modal clears synonyms on close', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    Livewire::test(VideoModal::class)
        ->call('openByTitle', 'Comprendre')
        ->assertSet('synonyms', ['Entendre'])
        ->call('close')
        ->assertSet('synonyms', []);
});

// ──────────────────────────────────────────────
// View rendering tests
// ──────────────────────────────────────────────

it('modal view shows synonyms section when synonyms exist', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Compréhension']);

    Livewire::test(VideoModal::class)
        ->call('openByTitle', 'Comprendre')
        ->assertSee('Synonymes')
        ->assertSee('Compréhension');
});

it('modal view hides synonyms section when no synonyms exist', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    makeVideoTheme('Bonjour', $theme->id, $syllabu->id);

    Livewire::test(VideoModal::class)
        ->call('openByTitle', 'Bonjour')
        ->assertDontSee('Synonymes');
});

// ──────────────────────────────────────────────
// API endpoint tests
// ──────────────────────────────────────────────

it('GET /api/v4/synonyms returns all synonyms grouped by word', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Compréhension']);
    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    $response = $this->getJson('/api/v4/synonyms');

    $response->assertOk()
        ->assertJsonPath('data.0.word', 'Comprendre')
        ->assertJsonCount(2, 'data.0.synonyms');
});

it('GET /api/v4/synonyms returns only words that have synonyms', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    makeVideoTheme('Sans synonymes', $theme->id, $syllabu->id);
    $video = makeVideoTheme('Avec synonyme', $theme->id, $syllabu->id);
    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Exemple']);

    $this->getJson('/api/v4/synonyms')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('GET /api/v4/synonyms/{id} returns synonyms for a specific word', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Compréhension']);
    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);

    $this->getJson("/api/v4/synonyms/{$video->id}")
        ->assertOk()
        ->assertJsonPath('data.video_theme_cloudinary_id', $video->id)
        ->assertJsonPath('data.word', 'Comprendre')
        ->assertJsonCount(2, 'data.synonyms');
});

it('GET /api/v4/synonyms/{id} returns empty array when word has no synonyms', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Bonjour', $theme->id, $syllabu->id);

    $this->getJson("/api/v4/synonyms/{$video->id}")
        ->assertOk()
        ->assertJsonPath('data.word', 'Bonjour')
        ->assertJsonCount(0, 'data.synonyms');
});

it('GET /api/v4/synonyms/{id} returns 404 for unknown ID', function () {
    $this->getJson('/api/v4/synonyms/999999')
        ->assertNotFound();
});

it('GET /api/v4/synonyms/{id} deduplicates synonyms across grouped variants', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video1  = makeVideoTheme('Aller (1)', $theme->id, $syllabu->id);
    $video2  = makeVideoTheme('Aller (2)', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video1->id, 'word' => 'Partir']);
    Synonym::create(['video_theme_cloudinary_id' => $video1->id, 'word' => 'Marcher']);
    Synonym::create(['video_theme_cloudinary_id' => $video2->id, 'word' => 'Partir']); // duplicate

    $this->getJson("/api/v4/synonyms/{$video1->id}")
        ->assertOk()
        ->assertJsonPath('data.word', 'Aller')
        ->assertJsonCount(2, 'data.synonyms');
});

it('GET /api/v4/synonyms/sync returns flat synonym records', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Entendre']);
    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Compréhension']);

    $this->getJson('/api/v4/synonyms/sync')
        ->assertOk()
        ->assertJsonStructure(['data', 'total', 'has_more', 'servidor_hora'])
        ->assertJsonPath('total', 2)
        ->assertJsonPath('has_more', false);
});

it('GET /api/v4/synonyms/sync returns only records updated after desde', function () {
    $syllabu = makeSyllabu();
    $theme   = makeTheme($syllabu->id);
    $video   = makeVideoTheme('Comprendre', $theme->id, $syllabu->id);

    $old = Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Ancien']);
    $old->updated_at = now()->subDays(10);
    $old->saveQuietly();

    Synonym::create(['video_theme_cloudinary_id' => $video->id, 'word' => 'Nouveau']);

    $desde = now()->subDay()->toIso8601String();

    $this->getJson('/api/v4/synonyms/sync?desde=' . urlencode($desde))
        ->assertOk()
        ->assertJsonPath('total', 1);
});
