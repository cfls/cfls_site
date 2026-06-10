<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class VideoCarousel extends Component
{
    public $vimeos;
    public $category;
    public $useSubtitled = false; // nuevo: estado del toggle

    public function mount($categorySlug, $useSubtitled = false)
    {
        $this->useSubtitled = $useSubtitled;

        $category = Category::where('slug', $categorySlug)->first();

        if ($category) {
            $this->category = $category;
            $this->vimeos = $category->videos()
                ->where('status', 1)
                ->orderBy('title')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.video-carousel');
    }
}