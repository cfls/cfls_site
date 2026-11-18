<?php

return [
    'notice_from' => env('CLOSURE_NOTICE_FROM', '2026-12-19'),
    'start' => env('CLOSURE_START', '2026-12-19'),
    'end' => env('CLOSURE_END', '2027-01-03'),   // incluido
    'reopen' => env('CLOSURE_REOPEN', '2027-01-04'),
    'timezone' => 'Europe/Brussels',

    // Solo en local: simula la fecha actual para ver el aviso (p. ej. 2026-12-20 10:00).
    'fake_now' => env('CLOSURE_FAKE_NOW'),
];
