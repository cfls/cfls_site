<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    // Formules de la Journée Immersive — prix par personne (€)
    public const FORMULES_IMMERSIVE = [
        'journee_soiree' => ['label' => 'Journée + soirée avec repas', 'prix' => 75],
        'soiree'         => ['label' => 'Soirée avec repas uniquement', 'prix' => 20],
    ];

    public const IBAN_IMMERSIVE = 'BE38 3100 5385 3072';

   protected $fillable = [
    'nom',
    'prenom',
    'email',
    'personnes',
    'type',
    'profil',
    'formule',
    'irhov',
    'prix',
];

protected $casts = [
    'irhov' => 'boolean',
];
}
