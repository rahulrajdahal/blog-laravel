<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Fetch the current user.",
     *     tags={"Users"},
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
