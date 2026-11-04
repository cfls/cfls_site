<?php

return [
    // Forcer l'affichage du calendrier hors décembre (tests en local)
    'force' => env('ADVENT_CALENDAR_FORCE', false),
];
