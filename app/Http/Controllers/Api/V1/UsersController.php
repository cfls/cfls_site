<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Mail\AccountDeletedMail;
use App\Models\QuizResult;
use App\Models\User;
use App\Models\VerifyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return UserResource::collection(User::paginate());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {


        $user->load(['subscriptions.plan']); // carga suscripciones y planes
        return new UserResource($user);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $user)
    {
        $user = User::findOrFail($user);

        $auth = $request->user();

        if (! $auth || $auth->id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, $auth->password)) {
            return response()->json([
                'message' => __('This password does not match our records.')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Notificación por correo
        Mail::to('info@cfls.be')
            ->cc($user->email)
            ->send(new AccountDeletedMail($user));


        app(DeletesUsers::class)->delete($user);
        VerifyCode::where('user_id', $user->id)->delete();
        QuizResult::where('user_id', $user->id)->delete();

        return response()->noContent();
    }
}
