<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InscriptionController extends Controller
{
    // Mostrar el formulario
    public function index()
    {
        return view('special.index');
    }

    // Guardar los datos y notificar al organizador
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'       => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'personnes' => 'required|integer|min:1|max:10',
        ], [
            'nom.required'       => 'Le nom complet est obligatoire.',
            'email.required'     => "L'adresse e-mail est obligatoire.",
            'email.email'        => "L'adresse e-mail n'est pas valide.",
            'personnes.required' => 'Veuillez indiquer le nombre de personnes.',
        ]);

        // Verificar si el email ya está registrado
        $yaExiste = Inscription::where('email', $validated['email'])->exists();

        if ($yaExiste) {
            return redirect()
                ->route('inscription.index')
                ->withFragment('resultat')
                ->with('duplicate', true)
                ->withInput();
        }

        // Guardar en BD
        $inscription = Inscription::create($validated);

        // Notificar al organizador
        Mail::raw(
            "Nouvelle inscription reçue !\n\n"
            . "Nom      : {$inscription->nom}\n"
            . "E-mail   : {$inscription->email}\n"
            . "Personnes: {$inscription->personnes}\n"
            . "Date     : {$inscription->created_at->format('d/m/Y H:i')}\n",
            function ($message) {
                $message
                    ->to(config('mail.organizer_email'))
                    ->subject('🎟 Nouvelle inscription — Lancement Nouveau Produit');
            }
        );

        return redirect()
            ->route('inscription.index')
            ->withFragment('resultat')
            ->with('success', true);
    }
}