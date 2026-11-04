<?php

namespace App\View\Components\Specials;

use App\Models\AdventVideo;
use Illuminate\View\Component;
use Illuminate\View\View;

class Calendaravdient extends Component
{
    public array $videos = [];

    public function __construct(public ?int $year = null)
    {
        $this->year ??= now()->year;
    }

    // Le calendrier n'est affiché qu'en décembre (sauf ADVENT_CALENDAR_FORCE=true)
    public function shouldRender(): bool
    {
        return now()->month === 12 || config('advent.force');
    }

    public function render(): View
    {
        // Seules les vidéos publiées sont envoyées au navigateur
        $this->videos = AdventVideo::query()
            ->visible($this->year)
            ->pluck('video_url', 'day')
            ->toArray();

        return view('components.specials.calendaravdient');
    }
}
