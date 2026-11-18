<?php

use Tests\TestCase;

uses(TestCase::class);

it('renders the closure banner with dates from the config', function () {
    $this->blade('<x-closure-banner />')
        ->assertSee('Le CFLS est fermé du 19/12/2026 au 03/01/2027 inclus (réouverture le 04/01/2027).', false)
        ->assertSee('Les commandes passées pendant cette période seront traitées à partir du 04/01.', false)
        ->assertSee('Fermeture de fin d’année', false)
        ->assertSee('role="status"', false)
        ->assertDontSee('localStorage', false);
});

it('follows the dates in the config', function () {
    config(['closure.start' => '2027-12-20', 'closure.end' => '2028-01-02', 'closure.reopen' => '2028-01-03']);

    $this->blade('<x-closure-banner />')
        ->assertSee('du 20/12/2027 au 02/01/2028', false)
        ->assertSee('à partir du 03/01.', false);
});
