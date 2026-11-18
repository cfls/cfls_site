<?php

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

afterEach(fn () => Carbon::setTestNow());

it('shows the closure banner on public pages only during the closure', function (string $at, bool $visible) {
    Carbon::setTestNow(Carbon::parse($at, 'Europe/Brussels'));

    $response = $this->get(route('boutique.index'))->assertOk();

    $visible
        ? $response->assertSee('Le CFLS est fermé du 19/12/2026', false)
        : $response->assertDontSee('Le CFLS est fermé du 19/12/2026', false);
})->with([
    'early december' => ['2026-12-01 00:00:00', false],
    'last day before closure' => ['2026-12-18 23:59:59', false],
    'closure starts' => ['2026-12-19 00:00:00', true],
    'during the closure' => ['2026-12-25 12:00:00', true],
    'last day of closure' => ['2027-01-03 23:59:59', true],
    'after reopening' => ['2027-01-04 00:00:00', false],
]);

// La migración de product_orders no tiene la columna `price` que usa
// OrderConfirmationMail::__construct (load('products')), así que se
// renderiza la plantilla del email con un pedido en memoria.
function confirmationEmailFor(string $createdAt): string
{
    $order = new Order([
        'delivery' => 'retrait',
        'member_discount' => 0,
        'total' => 10,
        'delivery_fee' => 0,
    ]);
    $order->id = 1;
    $order->created_at = Carbon::parse($createdAt, 'Europe/Brussels');
    $order->setRelation('user', User::factory()->make());
    $order->setRelation('products', collect());

    return view('emails.orders.confirmation', ['order' => $order])->render();
}

it('adds the closure notice to the confirmation email of an order placed during the closure', function () {
    expect(confirmationEmailFor('2026-12-20 10:00:00'))
        ->toContain('Votre commande sera traitée à partir du 04/01/2027.');
});

it('does not add the closure notice outside the closure', function () {
    expect(confirmationEmailFor('2026-12-18 10:00:00'))
        ->not->toContain('Votre commande sera traitée à partir du');
});

it('shows the processing date on the checkout only during the closure', function (string $at, bool $visible) {
    Carbon::setTestNow(Carbon::parse($at, 'Europe/Brussels'));

    // El formulario de pago solo se muestra con un usuario autenticado y el carrito lleno.
    $cart = [['id' => 1, 'name' => 'Livre', 'price' => 10, 'quantity' => 1, 'choix' => 0, 'image' => '/img/default.jpg']];

    $response = $this->actingAs(User::factory()->create())
        ->post(route('boutique.checkout'), ['cart_data' => json_encode($cart)])
        ->assertOk();

    $visible
        ? $response->assertSee('Votre commande sera traitée à partir du 04/01/2027.', false)
        : $response->assertDontSee('Votre commande sera traitée à partir du', false);
})->with([
    'last day before closure' => ['2026-12-18 23:59:59', false],
    'during the closure' => ['2026-12-19 00:00:00', true],
    'after reopening' => ['2027-01-04 00:00:00', false],
]);
