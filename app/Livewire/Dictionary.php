<?php

namespace App\Livewire;

use App\Mail\NewSuggestionMail;
use App\Models\Suggestion;
use App\Models\VideoTheme;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Dictionary extends Component
{
    use WithPagination;

    public string $title = 'Dictionnaire LSFB';

    /** Filtros */
    public string $search = '';
    public string $letter = 'tous';

    public int $perPage = 40;

    public ?array $selectedItem = null;
    public bool $showModal = false;

    /** Modal suggestion */
    public bool $showSuggestModal = false;
    public string $suggestWord = '';
    public bool $suggestionSent = false;

    protected $paginationTheme = 'tailwind';

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedLetter(): void
    {
        $this->resetPage();
    }

    public function setLetter($ltr): void
    {
        $this->letter = $ltr;
        $this->resetPage();
    }

    protected function query()
    {
        $query = VideoTheme::query()->where('active', true);

        if ($this->letter !== 'tous') {
            $query->where('title', 'like', strtoupper($this->letter) . '%');
        }

        if (trim($this->search) !== '') {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('title');
    }

    public function openVideo(int $id): void
    {
        if ($this->selectedItem && $this->selectedItem['id'] === $id) {
            $this->showModal = true;
            return;
        }

        $data = VideoTheme::where('id', $id)
            ->where('active', true)
            ->first();

        if (!$data) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Vidéo introuvable.'
            ]);
            return;
        }

        $this->selectedItem = [
            'id' => $data->id,
            'title' => $data->title,
            'url' => $data->url,
        ];

        $this->showModal = true;
    }

    public function openSuggestModal(): void
    {
        $this->suggestWord = $this->search;
        $this->suggestionSent = false;
        $this->showSuggestModal = true;
    }

    public function closeSuggestModal(): void
    {
        $this->showSuggestModal = false;
        $this->suggestWord = '';
        $this->suggestionSent = false;
    }

    public function submitSuggestion(): void
    {
        $this->validate([
            'suggestWord' => 'required|string|min:2|max:100',
        ], [
            'suggestWord.required' => "Merci d'indiquer un mot.",
            'suggestWord.min'      => 'Le mot est trop court.',
            'suggestWord.max'      => 'Le mot est trop long.',
        ]);

        $suggestion = Suggestion::create([
            'word'    => trim($this->suggestWord),
            'user_id' => auth()->id(),
            'ip'      => request()->ip(),
        ]);

        Mail::to('support@cfls.be')->send(new NewSuggestionMail($suggestion));

        $this->suggestionSent = true;
        $this->suggestWord = '';
    }

    public function render()
    {
        $items = $this->query()->paginate($this->perPage);

        return view('livewire.dictionary', [
            'items' => $items,
        ]);
    }
}