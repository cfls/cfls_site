<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscribe;
use Illuminate\Support\Str;

class SubscribeForm extends Component
{
    public $email;
    public $successMessage;

    public function subscribe()
    {
        // Nettoyer et normaliser l’e-mail
        $this->email = Str::lower(trim($this->email));



        $this->validate([
            'email' => 'required|email:rfc,dns|max:255',
        ], [
            'email.required' => 'Veuillez saisir votre adresse e-mail.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.max' => 'L’adresse e-mail est trop longue.',
        ]);

        // Vérifier si l’e-mail existe déjà
        if (Subscribe::where('email', $this->email)->exists()) {

            $this->addError('email', 'Cet e-mail est déjà inscrit.');
            return;
        }

        // Enregistrer l’inscription
        Subscribe::create([
            'email' => $this->email,
        ]);

        $this->successMessage = 'Inscription réussie !';
        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.subscribe-form');
    }
}