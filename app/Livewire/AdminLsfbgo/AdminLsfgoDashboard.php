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
                'title' => 'Traduire la LSFB',
                'type' => 'text',
                'img' => 'text.jpeg',
                'desc' => 'Écrire le mot vu en vidéo'
            ],
            [
                'title' => 'Choix',
                'type' => 'choice',
                'img' => 'choice.jpeg',
                'desc' => 'Choisir le bon mot parmi plusieurs'
            ],
//            [
//                'title' => 'Oui/Non',
//                'type' => 'yes-no',
//                'img' => 'yes-no.jpeg'
//            ],
            [
                'title' => 'Vidéos Choix',
                'type' => 'video-choice',
                'img' => 'video-choice.jpeg',
                'desc' => 'Choisir la vidéo qui correspond au mot'
            ],
            [
                'title' => 'Association',
                'type' => 'match',
                'img' => 'association.jpeg',
                'desc' => 'Relier chaque mot à sa vidéo'
            ],

        ];

        return view('livewire.admin-lsfgo-dashboard', compact('featured'));
    }
}