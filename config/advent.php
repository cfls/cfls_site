<?php

return [
    // Forcer l'affichage du calendrier hors décembre (tests en local)
    // En mode forcé, toutes les vidéos publiées sont débloquées sans attendre l'heure
    'force' => env('ADVENT_CALENDAR_FORCE', false),

    // Chaque case s'ouvre le jour J à cette heure (heure belge)
    'timezone' => 'Europe/Brussels',
    'unlock_hour' => 10,
];
