<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
   protected $fillable = [
    'nom',
    'prenom',
    'email',
    'personnes',
    'type',
    'profil',
    'irhov',
    'prix',
];

protected $casts = [
    'irhov' => 'boolean',
];
}