<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * @OA\Schema(
 *  schema="Register User",
 *  title="Register a new User",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="email",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="password",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="password_confirmation",
 * 		type="string"
 * 	)
 * )
 */
class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Register User")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Register a user"
     *     )
     * )
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
    /**
     * @OA\Get(
     *     path="/sanctum/csrf-cookie",
     *     summary="Generate a csrf cookie",
     *     tags={"CSRF"},
     *     @OA\Response(
     *         response=201,
     *         description="Create a CSRF token"
     *     )
     * )
     */
}
