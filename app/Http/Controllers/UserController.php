<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Register",
 *  description="Register a new user account.",
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
 *  @OA\Property(
 * 		property="password_confirmation",
 * 		type="string"
 * 	),
 * )
 */

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Fetch the current user.",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Fetch Logged in user"
     *     )
     * )
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json(['data' => $user, 'message' => 'User fetched!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
