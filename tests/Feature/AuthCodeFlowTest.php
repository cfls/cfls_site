<?php

use App\Mail\PasswordResetCodeMail;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

/*
|--------------------------------------------------------------------------
| Registro y verificación
|--------------------------------------------------------------------------
*/

it('registers a new user with is_active=0, purpose=register and sends code', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertOk();

    $user = User::firstWhere('email', 'alice@example.com');
    expect($user)->not->toBeNull()
        ->and((int) $user->is_active)->toBe(0)
        ->and($user->verification_purpose)->toBe('register')
        ->and($user->verification_code)->toHaveLength(6)
        ->and($user->verification_code_expires_at)->not->toBeNull();

    Mail::assertSent(VerificationCodeMail::class, fn ($mail) => $mail->hasTo('alice@example.com'));
});

it('rejects register when email already exists', function () {
    User::factory()->create(['email' => 'dup@example.com']);

    $this->postJson('/api/auth/register', [
        'name' => 'Bob',
        'email' => 'dup@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertStatus(422);
});

it('rejects register when password_confirmation does not match', function () {
    $this->postJson('/api/auth/register', [
        'name' => 'Bob',
        'email' => 'bob@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different',
    ])->assertStatus(422);
});

it('verifies email with correct code and activates account', function () {
    $user = User::factory()->create([
        'is_active' => 0,
        'verification_code' => '123456',
        'verification_purpose' => 'register',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $this->postJson('/api/auth/verify-email', [
        'email' => $user->email,
        'code' => '123456',
    ])->assertOk();

    $user->refresh();
    expect((int) $user->is_active)->toBe(1)
        ->and($user->verification_code)->toBeNull()
        ->and($user->verification_purpose)->toBeNull()
        ->and($user->verification_code_expires_at)->toBeNull();
});

it('rejects verify-email with incorrect code', function () {
    $user = User::factory()->create([
        'is_active' => 0,
        'verification_code' => '123456',
        'verification_purpose' => 'register',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $this->postJson('/api/auth/verify-email', [
        'email' => $user->email,
        'code' => '999999',
    ])->assertStatus(422);

    expect((int) $user->fresh()->is_active)->toBe(0);
});

it('rejects verify-email with expired code', function () {
    $user = User::factory()->create([
        'is_active' => 0,
        'verification_code' => '123456',
        'verification_purpose' => 'register',
        'verification_code_expires_at' => now()->subMinute(),
    ]);

    $this->postJson('/api/auth/verify-email', [
        'email' => $user->email,
        'code' => '123456',
    ])->assertStatus(422);

    expect((int) $user->fresh()->is_active)->toBe(0);
});

it('rejects verify-email when purpose is reset (wrong flow)', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'verification_code' => '123456',
        'verification_purpose' => 'reset',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $this->postJson('/api/auth/verify-email', [
        'email' => $user->email,
        'code' => '123456',
    ])->assertStatus(422);
});

/*
|--------------------------------------------------------------------------
| Login gating
|--------------------------------------------------------------------------
*/

it('lets an active user log in', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'password' => bcrypt('secret123'),
    ]);

    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ])->assertOk()
        ->assertJsonPath('data.token', fn ($v) => is_string($v) && $v !== '');
});

it('blocks login for inactive user with 403', function () {
    $user = User::factory()->create([
        'is_active' => 0,
        'password' => bcrypt('secret123'),
    ]);

    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ])->assertStatus(403);
});

it('returns 401 on wrong credentials', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'password' => bcrypt('secret123'),
    ]);

    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertStatus(401);
});

/*
|--------------------------------------------------------------------------
| Forgot / reset password
|--------------------------------------------------------------------------
*/

it('sends reset code for existing email and stores purpose=reset', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'verification_code' => null,
        'verification_purpose' => null,
        'verification_code_expires_at' => null,
    ]);

    $this->postJson('/api/auth/forgot-password', ['email' => $user->email])
        ->assertOk();

    $user->refresh();
    expect($user->verification_purpose)->toBe('reset')
        ->and($user->verification_code)->toHaveLength(6)
        ->and($user->verification_code_expires_at)->not->toBeNull();

    Mail::assertSent(PasswordResetCodeMail::class, fn ($mail) => $mail->hasTo($user->email));
});

it('does not reveal whether an email exists on forgot-password', function () {
    $this->postJson('/api/auth/forgot-password', ['email' => 'nobody@example.com'])
        ->assertOk();

    Mail::assertNothingSent();
});

it('resets password with valid code and invalidates the code', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'password' => bcrypt('old-password'),
        'verification_code' => '654321',
        'verification_purpose' => 'reset',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $this->postJson('/api/auth/reset-password', [
        'email' => $user->email,
        'code' => '654321',
        'password' => 'brand-new-pw',
        'password_confirmation' => 'brand-new-pw',
    ])->assertOk();

    $user->refresh();
    expect(password_verify('brand-new-pw', $user->password))->toBeTrue()
        ->and($user->verification_code)->toBeNull()
        ->and($user->verification_purpose)->toBeNull()
        ->and($user->verification_code_expires_at)->toBeNull();

    // Login funciona con la nueva contraseña
    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'brand-new-pw',
    ])->assertOk();
});

it('rejects reset-password with wrong code', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'password' => bcrypt('old-password'),
        'verification_code' => '654321',
        'verification_purpose' => 'reset',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $this->postJson('/api/auth/reset-password', [
        'email' => $user->email,
        'code' => '000000',
        'password' => 'brand-new-pw',
        'password_confirmation' => 'brand-new-pw',
    ])->assertStatus(422);

    expect(password_verify('old-password', $user->fresh()->password))->toBeTrue();
});

it('rejects reset-password with expired code', function () {
    $user = User::factory()->create([
        'is_active' => 1,
        'password' => bcrypt('old-password'),
        'verification_code' => '654321',
        'verification_purpose' => 'reset',
        'verification_code_expires_at' => now()->subMinute(),
    ]);

    $this->postJson('/api/auth/reset-password', [
        'email' => $user->email,
        'code' => '654321',
        'password' => 'brand-new-pw',
        'password_confirmation' => 'brand-new-pw',
    ])->assertStatus(422);
});

/*
|--------------------------------------------------------------------------
| Reenvío
|--------------------------------------------------------------------------
*/

it('resends verification code and regenerates it', function () {
    $user = User::factory()->create([
        'is_active' => 0,
        'verification_code' => '111111',
        'verification_purpose' => 'register',
        'verification_code_expires_at' => now()->subMinutes(5),
    ]);

    $this->postJson('/api/auth/verify-code', ['email' => $user->email])
        ->assertOk();

    $user->refresh();
    expect($user->verification_code)->not->toBe('111111')
        ->and($user->verification_code)->toHaveLength(6);

    Mail::assertSent(VerificationCodeMail::class);
});

it('throttles resend when a recent code exists', function () {
    // Code issued 10 seconds ago (expiration = 15min from issue → 14min50s from now).
    $user = User::factory()->create([
        'is_active' => 0,
        'verification_code' => '111111',
        'verification_purpose' => 'register',
        'verification_code_expires_at' => now()->addMinutes(15)->subSeconds(10),
    ]);

    $this->postJson('/api/auth/verify-code', ['email' => $user->email])
        ->assertStatus(429);
});
