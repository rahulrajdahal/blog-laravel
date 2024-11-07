<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * @OA\Schema(
 *  schema="Login",
 *  description="Login to user account.",
 * 	@OA\Property(
 * 		property="email",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="password",
 * 		type="string"
 * 	),
 * )
 */


class AuthController extends Controller
{
    use ValidatesRequests;

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Create a new user account",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Register"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Register")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $user = (new CreateNewUser)->create($request->all());
        return response()->json($user, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login a registered user.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Login"),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Login")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login successful."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => "Invalid Credentials"], 400);
        }

        if (Auth::attempt($request->all())) {
            $user->tokens()->delete();

            $access_token = $user->createToken('access_token')->plainTextToken;

            return response()->json(['access_token' => $access_token, 'message' => "Login Successful."], 201);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout current user.",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (Throwable $e) {
            if ($e->getMessage() == 'Call to undefined method Laravel\\Sanctum\\TransientToken::delete()') {
                return response()->json(['error' => $e->getMessage(), 'message' => 'Invalid Access Token'], 500);
            }
            return response()->json(['error' => $e->getMessage(), 'message' => 'Internal Server Error'], 500);
        }
    }
}
