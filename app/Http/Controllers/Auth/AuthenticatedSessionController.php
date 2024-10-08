<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *  schema="Login User",
 *  title="Login registered User",
 * 	@OA\Property(
 * 		property="email",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="password",
 * 		type="string"
 * 	)
 * )
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login a user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Login User")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Login a user"
     *     )
     * )
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
