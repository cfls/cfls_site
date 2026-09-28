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


    // Mostrar la página de Visual Vernacular
    public function visualVernacular()
    {
        return view('special.visual_vernacular');   
    }
  
    public function storeVisualVernacular(Request $request)
    {
       
        $validated = $request->validate([
            'nom'    => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'profil' => 'required|in:adulte,adolescent',
            'irhov'  => 'nullable|in:oui',
        ], [
            'nom.required'    => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required'  => "L'adresse e-mail est obligatoire.",
            'email.email'     => "L'adresse e-mail n'est pas valide.",
            'profil.required' => 'Veuillez sélectionner votre profil (Adulte ou Adolescent).',
            'profil.in'       => 'Le profil sélectionné est invalide.',
        ]);
 
        // Normalisation de la case IRHOV — absente = false
        $validated['irhov'] = $request->input('irhov') === 'oui';
 
        // Calcul du prix selon le statut élève IRHOV
        $validated['prix'] = $validated['irhov'] ? 75 : 100;
 
        // Vérification d'une inscription existante pour cet événement
        $dejaInscrit = Inscription::where('email', $validated['email'])
                                  ->where('type', 'stage_vv')
                                  ->exists();
 
        if ($dejaInscrit) {
            return redirect()
                ->route('inscription.visual_vernacular')
                ->withFragment('resultat')
                ->with('duplicate', true)
                ->withInput();
        }
 
        $inscription = Inscription::create(array_merge($validated, ['type' => 'stage_vv']));
 
        $irhovLabel = $inscription->irhov ? 'Oui (tarif réduit — 75 €)' : 'Non (100 €)';
 
        // Notification à l'organisateur
        Mail::raw(
            "Nouvelle inscription — Stage Visual Vernacular\n\n"
            . "Nom      : {$inscription->nom}\n"
            . "Prénom   : {$inscription->prenom}\n"
            . "E-mail   : {$inscription->email}\n"
            . "Profil   : {$inscription->profil}\n"
            . "IRHOV    : {$irhovLabel}\n"
            . "Prix     : {$inscription->prix} €\n"
            . "Date     : {$inscription->created_at->format('d/m/Y H:i')}\n",
            fn ($message) => $message
                ->to(config('mail.organizer_email'))
                ->subject('🎟 Nouvelle inscription — Stage VV 06–08/05/2026')
        );
 
        // E-mail de confirmation au participant
        Mail::raw(
            "Bonjour {$inscription->prenom},\n\n"
            . "Votre inscription au stage Visual Vernacular a bien été enregistrée !\n\n"
            . "📅 Du 6 au 8 mai 2026 — de 9h30 à 16h00\n"
            . "📍 École IRHOV — Rue Monulphe 80, 4000 Liège\n"
            . "💶 Montant à régler : {$inscription->prix} €\n\n"
            . ($inscription->irhov
                ? "⚠️  N'oubliez pas d'apporter une preuve de votre statut d'élève IRHOV "
                  . "(carte étudiante ou certificat de scolarité).\n\n"
                : '')
            . "Nous vous contacterons prochainement pour les modalités de paiement.\n\n"
            . "À très bientôt,\nL'équipe organisatrice",
            fn ($message) => $message
                ->to($inscription->email)
                ->subject('✅ Confirmation — Stage Visual Vernacular')
        );
 
        return redirect()
            ->route('inscription.visual_vernacular')
            ->withFragment('resultat')
            ->with('success', true);
       }

    // Mostrar la página de la Journée Immersive
    public function immersive()
    {
        return view('special.journee_immersive');
    }

    public function storeImmersive(Request $request)
    {
        $validated = $request->validate([
            'nom'       => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'personnes' => 'required|integer|min:1|max:10',
            'formule'   => 'required|in:' . implode(',', array_keys(Inscription::FORMULES_IMMERSIVE)),
        ], [
            'nom.required'       => 'Le nom complet est obligatoire.',
            'email.required'     => "L'adresse e-mail est obligatoire.",
            'email.email'        => "L'adresse e-mail n'est pas valide.",
            'personnes.required' => 'Veuillez indiquer le nombre de personnes.',
            'formule.required'   => 'Veuillez choisir une formule.',
            'formule.in'         => 'La formule sélectionnée est invalide.',
        ]);

        // Prix total = prix de la formule × nombre de personnes
        $formule = Inscription::FORMULES_IMMERSIVE[$validated['formule']];
        $validated['prix'] = $formule['prix'] * $validated['personnes'];

        // Vérification d'une inscription existante pour cet événement
        $dejaInscrit = Inscription::where('email', $validated['email'])
                                  ->where('type', 'journee_immersive')
                                  ->exists();

        if ($dejaInscrit) {
            return redirect()
                ->route('inscription.journee_immersive')
                ->withFragment('resultat')
                ->with('duplicate', true)
                ->withInput();
        }

        $inscription = Inscription::create(array_merge($validated, ['type' => 'journee_immersive']));

        // Notification à l'organisateur — un échec d'envoi ne doit pas bloquer l'inscription déjà enregistrée
        try {
            Mail::raw(
                "Nouvelle inscription — Journée Immersive\n\n"
                . "Nom      : {$inscription->nom}\n"
                . "E-mail   : {$inscription->email}\n"
                . "Personnes: {$inscription->personnes}\n"
                . "Formule  : {$formule['label']} ({$formule['prix']} € / pers.)\n"
                . "Total    : {$inscription->prix} €\n"
                . "Date     : {$inscription->created_at->format('d/m/Y H:i')}\n",
                fn ($message) => $message
                    ->to(config('mail.organizer_email'))
                    ->subject('🎟 Nouvelle inscription — Journée Immersive 14/11/2026')
            );
        } catch (\Throwable $e) {
            report($e);
        }

        // E-mail de confirmation au participant avec les modalités de paiement
        try {
            Mail::raw(
                "Bonjour {$inscription->nom},\n\n"
                . "Votre inscription à la Journée Immersive a bien été enregistrée !\n\n"
                . "📅 Samedi 14 novembre 2026\n"
                . "📍 Rue au Bois 365B, 1150 Bruxelles\n"
                . "🎟 Formule : {$formule['label']} — {$inscription->personnes} personne(s)\n"
                . "💶 Montant à régler : {$inscription->prix} €\n\n"
                . "Merci d'effectuer le virement sur le compte " . Inscription::IBAN_IMMERSIVE . "\n"
                . "avec la communication : Journée Immersive — {$inscription->nom}\n\n"
                . "À très bientôt,\nL'équipe organisatrice",
                fn ($message) => $message
                    ->to($inscription->email)
                    ->subject('✅ Confirmation — Journée Immersive 14/11/2026')
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('inscription.journee_immersive')
            ->withFragment('resultat')
            ->with('success', true)
            ->with('prix', $inscription->prix);
    }
}