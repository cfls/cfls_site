<?php

namespace App\Livewire;

use App\Models\Newsletter;
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
        if (Newsletter::where('email', $this->email)->exists()) {

            $this->addError('email', 'Cet e-mail est déjà inscrit.');
            return;
        }

        // Enregistrer l’inscription
        Newsletter::create([
            'email' => $this->email,
            'date' => now(),
            'newsletter' => 1,
        ]);

        $this->successMessage = 'Inscription réussie !';
        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.subscribe-form');
    }
}