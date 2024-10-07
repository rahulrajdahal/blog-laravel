<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Laravel Blog api documentation."
 * )
 */
abstract class Controller
{
    //
    public function notFoundError($model)
    {
        return "{$model} not found!";
    }
}
