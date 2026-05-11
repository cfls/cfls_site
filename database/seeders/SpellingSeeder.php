<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spelling;

class SpellingSeeder extends Seeder
{
    public function run(): void
    {
        $words = [

            // ─── EASY (100) ───────────────────────────────────────────
            'easy' => [
                'Ami', 'Art', 'Bas', 'Beau', 'Bien',
                'Blanc', 'Boire', 'Bon', 'Bras', 'Brun',
                'Bus', 'But', 'Cafe', 'Calme', 'Car',
                'Carte', 'Chat', 'Chien', 'Clair', 'Clef',
                'Corps', 'Court', 'Doux', 'Droit', 'Eau',
                'Film', 'Fort', 'Froid', 'Gros', 'Grand',
                'Homme', 'Jardin', 'Jeu', 'Joli', 'Jouer',
                'Journal', 'Lent', 'Lion', 'Lire', 'Long',
                'Loup', 'Maman', 'Matin', 'Mer', 'Mince',
                'Mois', 'Mort', 'Mouton', 'Nager', 'Nuit',
                'Noir', 'Non', 'Normal', 'Oui', 'Parc',
                'Pain', 'Papa', 'Parc', 'Petit', 'Peur',
                'Pied', 'Pont', 'Port', 'Poulet', 'Propre',
                'Rat', 'Rire', 'Robot', 'Rose', 'Rouge',
                'Rue', 'Sac', 'Sel', 'Simple', 'Sol',
                'Sport', 'Taxi', 'Toit', 'Tom', 'Train',
                'Vert', 'Vieux', 'Voir', 'Vrai', 'Wagon',
                'Ami', 'Ballon', 'Bateau', 'Beau', 'Bleu',
                'Bois', 'Bouche', 'Bruit', 'Chaud', 'Ciel',
                'Dix', 'Dos', 'Feu', 'Fleur', 'Fond',
                'Fruit', 'Gare', 'Herbe', 'Hiver', 'Jour',
            ],

            // ─── MEDIUM (100) ─────────────────────────────────────────
            'medium' => [
                'Abandonner', 'Accepter', 'Accompagner', 'Adorer', 'Aimer',
                'Ajouter', 'Aller', 'Allumer', 'Amour', 'Animal',
                'Appeler', 'Apporter', 'Arbre', 'Argent', 'Arriver',
                'Attendre', 'Avoir', 'Baigner', 'Banque', 'Bouger',
                'Calculer', 'Canal', 'Chanter', 'Chercher', 'Choisir',
                'Classe', 'Commerce', 'Commencer', 'Compter', 'Conduire',
                'Content', 'Contrat', 'Copier', 'Couper', 'Courir',
                'Couvrir', 'Croiser', 'Cultiver', 'Danger', 'Danser',
                'Demander', 'Descendre', 'Dessiner', 'Devenir', 'Dire',
                'Discuter', 'Diviser', 'Donner', 'Dormir', 'Dossier',
                'Dresser', 'Employer', 'Enfant', 'Entrer', 'Essayer',
                'Fabriquer', 'Fermer', 'Finir', 'Fixer', 'Former',
                'Fournir', 'Gagner', 'Garage', 'Garder', 'Gouverner',
                'Grandir', 'Grimper', 'Habiller', 'Habiter', 'Imaginer',
                'Installer', 'Inviter', 'Joindre', 'Jouet', 'Lancer',
                'Laver', 'Livrer', 'Louer', 'Lourd', 'Maison',
                'Manger', 'Marcher', 'Mesurer', 'Montagne', 'Monter',
                'Montrer', 'Moteur', 'Multiplier', 'Nommer', 'Oublier',
                'Ouvrir', 'Pantalon', 'Participer', 'Partir', 'Passer',
                'Patron', 'Payer', 'Penser', 'Perdre', 'Plaire',
            ],

            // ─── HARD (100) ───────────────────────────────────────────
            'hard' => [
                'Accomplissement', 'Administrateur', 'Alphabetique', 'Ambivalent', 'Amplificateur',
                'Apprentissage', 'Approximatif', 'Arbitrairement', 'Architecturer', 'Argumentaire',
                'Authentification', 'Bibliographique', 'Bureaucratique', 'Cardiovasculaire', 'Chronologique',
                'Circumnaviguer', 'Collaboratif', 'Communautaire', 'Competitivement', 'Comportemental',
                'Conceptualiser', 'Confidentialite', 'Conglomerats', 'Connexion', 'Contemporain',
                'Contradictoire', 'Conventionnellement', 'Corporatif', 'Correspondance', 'Cryptographique',
                'Decentraliser', 'Democratisation', 'Departementaliser', 'Desorganisation', 'Deterministe',
                'Differentiellement', 'Dimensionnellement', 'Disciplinaire', 'Discriminatoire', 'Documentaire',
                'Dynamiquement', 'Electrostatique', 'Elementairement', 'Emphatiquement', 'Encryptement',
                'Environnementale', 'Epistemologique', 'Ergonomiquement', 'Essentiellement', 'Exceptionnellement',
                'Experimentalement', 'Explicitement', 'Extraordinairement', 'Fonctionnellement', 'Fondamentalement',
                'Fractionnellement', 'Fragmentairement', 'Gouvernementale', 'Grammaticalement', 'Hierarchiquement',
                'Horizontalement', 'Hypothetiquement', 'Identificateur', 'Implementer', 'Independamment',
                'Indispensablement', 'Individuellement', 'Industriellement', 'Informatiquement', 'Institutionnel',
                'Intentionnellement', 'Interconnecter', 'Internationalement', 'Interpersonnel', 'Interrogativement',
                'Involontairement', 'Irreversiblement', 'Juridiquement', 'Justificatif', 'Kinesthesique',
                'Lexicographique', 'Linguistiquement', 'Longitudinalement', 'Mathematiquement', 'Methodologiquement',
                'Microprocesseur', 'Morphologiquement', 'Multiplicativement', 'Narrativement', 'Objectivement',
                'Operationnellement', 'Organisationnellement', 'Orthographiquement', 'Paradoxalement', 'Parallelisme',
                'Particulierement', 'Perpendiculairement', 'Phenomenalement', 'Philosophiquement', 'Physiologiquement',
                'Pluridisciplinaire', 'Politiquement', 'Pratiquement', 'Professionnellement', 'Progressivement',
            ],
        ];

        foreach ($words as $difficulty => $list) {
            foreach ($list as $word) {
                Spelling::firstOrCreate(
                    ['word' => $word],
                    [
                        'difficulty' => $difficulty,
                        'active'     => true,
                    ]
                );
            }
        }
    }
}