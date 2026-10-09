<?php

namespace App\Livewire;

use App\Models\VideoTheme;
use Livewire\Component;

class VideoModal extends Component
{
    public bool $open = false;
    public array $videos = [];
    public string $displayTitle = '';

    protected $listeners = ['openVideoModal' => 'openByTitle'];

    public function openByTitle(string $title): void
    {
        $stripBase = static function (string $t): string {
            $pos = mb_strpos($t, ' (');
            if ($pos === false || $pos === 0) return $t;
            return preg_match('/^\d/', mb_substr($t, $pos + 2)) ? trim(mb_substr($t, 0, $pos)) : $t;
        };

        $items = VideoTheme::query()
            ->where('active', true)
            ->where(function ($q) use ($title) {
                $q->where('title', $title)
                  ->orWhere('title', 'like', $title . ' (%');
            })
            ->orderBy('title')
            ->get()
            ->filter(fn($data) => $stripBase($data->title) === $title)
            ->values();

        if ($items->isEmpty()) {
            $this->dispatch('notify', type: 'error', message: 'Vidéo introuvable');
            return;
        }

        $this->videos = $items->map(function ($data) {
            $videoId = pathinfo($data->url, PATHINFO_FILENAME);
            return [
                'id'     => $data->id,
                'title'  => $data->title,
                'url'    => "https://res.cloudinary.com/dmhdsjmzf/video/upload/q_auto,w_1280,f_auto,c_limit/{$videoId}.mp4",
                'poster' => "https://res.cloudinary.com/dmhdsjmzf/video/upload/so_0,w_400,q_auto:low/{$videoId}.jpg",
            ];
        })->values()->toArray();

        $this->displayTitle = $title;
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->videos = [];
        $this->displayTitle = '';
    }

    public function render()
    {
        return view('livewire.video-modal');
    }
}
