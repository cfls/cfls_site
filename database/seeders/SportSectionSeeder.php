<?php

namespace Database\Seeders;

use App\Models\SportSection;
use App\Models\SportSubsection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SportSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Vaciar para poder ejecutar el seeder varias veces sin duplicados
        Schema::disableForeignKeyConstraints();
        SportSubsection::truncate();
        SportSection::truncate();
        Schema::enableForeignKeyConstraints();

        // 'subs' => [video_number, título]
        // video_number = número de la carpeta en Cloudinary ("Ekho Sport/1. ACTIONS DE BASSE" => 1)
        $data = [
            ['number' => 'I',   'title' => 'VOCABULAIRE DE COACHING ÉKHÔ SPORT', 'subtitle' => 'communication rapide', 'page' => 15, 'subs' => [
                [1, 'Actions de base'],
                [2, 'Mouvement'],
                [3, 'Communication de jeu'],
            ]],
            ['number' => 'II',  'title' => 'VOCABULAIRE TACTIQUE', 'subtitle' => 'jeu collectif', 'page' => 24, 'subs' => [
                [4, 'Positions'],
                [5, 'Organisation'],
                [6, 'Principes tactiques simples'],
            ]],
            ['number' => null,  'title' => 'SITUATIONS DE JEU', 'subtitle' => null, 'page' => 32, 'subs' => [
                [7,    'Situations de jeu'],
                [8,    "Avant l'exercice"],
                [null, "Pendant l'exercice"],          // no hay carpeta en Cloudinary
                [9,    'Discipline et cadre'],
            ]],
            ['number' => null,  'title' => 'OBJECTIF', 'subtitle' => null, 'page' => 41, 'subs' => []],
            ['number' => 'I',   'title' => 'MOTS DE COACHING – LARGEUR', 'subtitle' => null, 'page' => 42, 'subs' => [
                [10, 'Mots de coaching – Largeur'],
                [11, 'Phrases simples – Largeur / Écartement'],
            ]],
            ['number' => 'II',  'title' => 'MOTS DE COACHING – PROFONDEUR', 'subtitle' => null, 'page' => 48, 'subs' => [
                [12, 'Mots de coaching – Profondeur'],
                [13, 'Phrases simples – Profondeur / Attaque'],
            ]],
            ['number' => 'III', 'title' => 'ALTERNER LARGEUR / PROFONDEUR', 'subtitle' => 'TRÈS IMPORTANT', 'page' => 55, 'subs' => [
                [14, 'Consignes clés – Largeur / Profondeur / Choix de jeu'],
            ]],
            ['number' => 'IV',  'title' => 'MOTS POUR LA PRISE DE DÉCISION', 'subtitle' => null, 'page' => 63, 'subs' => [
                [15, 'Si espace sur le côté'],
                [16, 'Si espace devant'],
                [17, 'Situation match – Communication et prise de décision'],
            ]],
            ['number' => 'V',   'title' => 'CORRECTIONS', 'subtitle' => 'TRÈS UTILES', 'page' => 75, 'subs' => [
                [18, 'Mauvais choix – Il joue profondeur au lieu de largeur'],
                [19, "Mauvais choix – Il reste côté alors qu'il faut avancer"],
            ]],
            ['number' => 'VI',  'title' => 'SÉQUENCES DE COACHING', 'subtitle' => 'TRÈS EFFICACE', 'page' => 82, 'subs' => [
                [20, 'Séquences de coaching'],
            ]],
            ['number' => 'VII', 'title' => 'RÈGLES SIMPLES À LEUR DONNER', 'subtitle' => null, 'page' => 86, 'subs' => [
                [21, 'Règles simples à leur donner'],
            ]],
        ];

        foreach ($data as $i => $item) {
            $section = SportSection::create([
                'number'     => $item['number'],
                'title'      => $item['title'],
                'subtitle'   => $item['subtitle'],
                'page'       => $item['page'],
                'sort_order' => $i + 1,
            ]);

            foreach ($item['subs'] as $j => [$videoNumber, $subTitle]) {
                $section->subsections()->create([
                    'video_number' => $videoNumber,
                    'title'        => $subTitle,
                    'url'          => null,
                    'status'       => true,
                    'sort_order'   => $j + 1,
                ]);
            }
        }
    }
}
