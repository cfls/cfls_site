<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Mail\PasswordResetCodeMail;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthControllerV4 extends Controller
{
    use ApiResponses;

    /** Ventana de validez del código (register o reset). */
    private const CODE_TTL_MINUTES = 15;

    /** Segundos mínimos entre reenvíos de código para el mismo email. */
    private const RESEND_THROTTLE_SECONDS = 60;

    public function login(LoginUserRequest $request): JsonResponse
    {
        $request->validated($request->all());

        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Email ou mot de passe incorrect.', 401);
        }

        $user = User::firstWhere('email', $request->email);

        if (! $user->is_active) {
            return $this->error(
                "Votre compte n'est pas encore activé. Veuillez vérifier votre adresse e-mail.",
                403
            );
        }

        return $this->ok('Authenticated', [
            'token' => $user->createToken(
                'API Token for '.$user->email,
                ['*'],
                now()->addMonth()
            )->plainTextToken,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('Logged out');
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $verificationCode = (string) random_int(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'etudiant',
            'is_active' => 0,
            'verification_code' => $verificationCode,
            'verification_purpose' => 'register',
            'verification_code_expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
        ]);

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $verificationCode));
        } catch (\Throwable $e) {
            return $this->error("Échec de l'envoi du code de vérification.", 500);
        }

        return $this->ok('Utilisateur enregistré. Vérifiez votre email pour le code de validation.');
    }

    /**
     * Reenvía el código de verificación al usuario. Regenera el código y
     * la expiración, respetando el purpose actual (register o reset).
     * Aplica throttling para evitar spam.
     */
    public function verifyCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! in_array($user->verification_purpose, ['register', 'reset'], true)) {
            return $this->ok('Si cette adresse e-mail correspond à un compte, un code de vérification vous sera envoyé.');
        }

        if ($this->isThrottled($user)) {
            return $this->error('Veuillez patienter avant de demander un nouveau code.', 429);
        }

        $newCode = (string) random_int(100000, 999999);
        $user->update([
            'verification_code' => $newCode,
            'verification_code_expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
        ]);

        try {
            if ($user->verification_purpose === 'reset') {
                Mail::to($user->email)->send(new PasswordResetCodeMail($user, $newCode));
            } else {
                Mail::to($user->email)->send(new VerificationCodeMail($user, $newCode));
            }
        } catch (\Throwable $e) {
            return $this->error("Échec de l'envoi du code.", 500);
        }

        return $this->ok('Code renvoyé.');
    }

    /**
     * Verifica el código de activación de cuenta (purpose = register) y
     * activa al usuario. Los códigos de reset se validan por separado en
     * el flujo de recuperación.
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('verification_purpose', 'register')
            ->first();

        if (! $user) {
            return $this->error('Code de vérification invalide.', 422);
        }

        if ($this->isCodeExpired($user)) {
            return $this->error('Ce code a expiré. Demandez-en un nouveau.', 422);
        }

        $user->update([
            'is_active' => 1,
            'verification_code' => null,
            'verification_purpose' => null,
            'verification_code_expires_at' => null,
        ]);

        return $this->ok('Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->ok('User retrieved', $request->user());
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return $this->ok('Profile updated', $user);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! password_verify($request->current_password, $user->password)) {
            return $this->error('Current password is incorrect', 422);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return $this->ok('Password updated');
    }

    /**
     * Genera y envía un código de recuperación de contraseña.
     * Devuelve siempre 200 con mensaje genérico para no revelar si el email existe.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $genericResponse = $this->ok('Si cette adresse e-mail correspond à un compte, un code de vérification vous sera envoyé.');

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $genericResponse;
        }

        if ($this->isThrottled($user)) {
            return $genericResponse;
        }

        $code = (string) random_int(100000, 999999);
        $user->update([
            'verification_code' => $code,
            'verification_purpose' => 'reset',
            'verification_code_expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
        ]);

        try {
            Mail::to($user->email)->send(new PasswordResetCodeMail($user, $code));
        } catch (\Throwable $e) {
            return $this->error("Échec de l'envoi du code.", 500);
        }

        return $genericResponse;
    }

    /**  temporal por la version LSFBGO V3 */
    public function forgotPasswordTemporal(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent'])
            : response()->json(['message' => 'Unable to send reset link'], 500);
    }

    /**
     * Valida el código de reset y establece la nueva contraseña en un
     * único paso. Invalida el código tras el cambio.
     */
    public function resetPasswordWithCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('verification_purpose', 'reset')
            ->first();

        if (! $user) {
            return $this->error('Code de vérification invalide.', 422);
        }

        if ($this->isCodeExpired($user)) {
            return $this->error('Ce code a expiré. Demandez-en un nouveau.', 422);
        }

        $user->update([
            'password' => bcrypt($request->password),
            'verification_code' => null,
            'verification_purpose' => null,
            'verification_code_expires_at' => null,
        ]);

        return $this->ok('Votre mot de passe a été mis à jour.');
    }

    private function isCodeExpired(User $user): bool
    {
        return $user->verification_code_expires_at !== null
            && $user->verification_code_expires_at->isPast();
    }

    private function isThrottled(User $user): bool
    {
        $expiresAt = $user->verification_code_expires_at;

        if ($expiresAt === null || $expiresAt->isPast()) {
            return false;
        }

        $issuedAt = $expiresAt->copy()->subMinutes(self::CODE_TTL_MINUTES);
        $elapsed = now()->diffInSeconds($issuedAt, absolute: true);

        return $elapsed < self::RESEND_THROTTLE_SECONDS;
    }
}
