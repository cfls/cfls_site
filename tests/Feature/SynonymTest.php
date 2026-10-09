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
