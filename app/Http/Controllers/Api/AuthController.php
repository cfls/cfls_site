<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request): JsonResponse
    {

        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $request->email);

        if (!$user->code && $user->verification_code) {
            return $this->error('Your account is not verified. Please check your email.', 403);
        }

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API Token for ' . $user->email,
                    ['*'],
                    now()->addMonth()
                )->plainTextToken,
                'user'  => $user, // 👈 aquí
            ]
        );
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

        // Generar código aleatorio de 6 dígitos
        $verificationCode = rand(100000, 999999);

        // Crear el usuario con estado inactivo
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'etudiant',
            'is_active' => 0,
            'verification_code' => $verificationCode,
        ]);

        // Enviar el correo con el código de verificación
        try {
            \Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($user, $verificationCode));

        } catch (\Exception $e) {
            return $this->error('Échec de l’envoi du code de vérification : ' . $e->getMessage(), 500);
        }

        return $this->ok('Utilisateur enregistré. Vérifiez votre email pour le code de validation.');
    }


    public function verifyCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',

        ]);

        $user = User::where('email', $request->email)->first();

        // Enviar el correo con el código de verificación
        try {
            \Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($user, $user->verification_code));
           
        } catch (\Exception $e) {
            return $this->error('Échec de l’envoi du code de vérification : ' . $e->getMessage(), 500);
        }

        return $this->ok('Resend code:', $user->verification_code);

    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();

        if (!$user) {
            return $this->error('Code de vérification invalide.', 422);
        }

        $user->update([
            'is_active' => 1,
            'verification_code' => null,
        ]);

        return $this->ok('Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->ok(
            'User retrieved',
            $request->user()
        );
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();



        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
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

        if (!password_verify($request->current_password, $user->password)) {
            return $this->error('Current password is incorrect', 422);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return $this->ok('Password updated');
    }

    public function forgotPassword(Request $request): JsonResponse
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


}
