<?php

it('shows that immersive registrations are closed', function () {
    $this->get(route('inscription.journee_immersive'))
        ->assertOk()
        ->assertSee('Inscriptions clôturées')
        ->assertDontSee('<form method="POST"', false);
});

it('rejects immersive registration submissions after closure', function () {
    $this->post(route('inscription.journee_immersive.store'), [])
        ->assertRedirect(route('inscription.journee_immersive') . '#resultat');
});
