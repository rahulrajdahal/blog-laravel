<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    // your routes here


    Route::prefix('/v1')->group(function () {
        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware('guest')
            ->name('register');


        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware('guest')
            ->name('login');


        // Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        //     return $request->user();
        // });

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/user', [UserController::class, 'show']);

            // Tags Routes
            Route::controller(TagController::class)->group(function () {
                $uriWithId = '/tags/{id}';

                Route::get('/tags', 'index');
                Route::get($uriWithId, 'show');
                Route::post('/tags', 'store');
                Route::patch($uriWithId, 'update');
                Route::put($uriWithId, 'updatePUT');
                Route::delete($uriWithId, 'destroy');
            });

            // Categories Routes
            Route::controller(CategoryController::class)->group(function () {
                $uriWithId = '/categories/{id}';

                Route::get('/categories', 'index');
                Route::get($uriWithId, 'show');
                Route::post('/categories', 'store');
                Route::patch($uriWithId, 'update');
                Route::put($uriWithId, 'updatePUT');
                Route::delete($uriWithId, 'destroy');
            });

            // Posts Routes
            Route::controller(PostController::class)->group(function () {
                $uriWithId = '/posts/{id}';

                Route::get('/posts', 'index');
                Route::get($uriWithId, 'show');
                Route::post('/posts', 'store');
                Route::patch($uriWithId, 'update');
                Route::put($uriWithId, 'updatePUT');
                Route::delete($uriWithId, 'destroy');
            });
        });
    });
});
