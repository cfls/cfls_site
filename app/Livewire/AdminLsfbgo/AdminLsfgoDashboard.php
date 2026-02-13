<?php

namespace App\Livewire\AdminLsfbgo;

use App\Models\Syllabu;
use App\Models\Theme;
use Livewire\Component;

class AdminLsfgoDashboard extends Component
{
    public $syllabus;
    public $themes = [];
    public $selectedSyllabus = '';
    public $selectedTheme = '';

    public function mount()
    {
        $this->syllabus = Syllabu::all();
    }

    public function ShowTheme($value)
    {
        $this->selectedSyllabus = $value;

        if ($value) {
            $this->themes = Theme::where('syllabu_id', $value)->get();
        } else {
            $this->themes = [];
        }
    }

    public function render()
    {
        $featured = [
            [
                'title' => 'Textes',
                'type' => 'text',
            ],
            [
                'title' => 'Choix',
                'type' => 'choice',
            ],
            [
                'title' => 'Choix Oui/Non',
                'type' => 'yes-no',
            ],
            [
                'title' => 'Vidéos Choix',
                'type' => 'video-choice',
            ],

        ];

        return view('livewire.admin-lsfgo-dashboard', compact('featured'));
    }
}