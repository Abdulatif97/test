<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Route for admin permissions




Route::group(["middleware" => ['auth:api'], "namespace" => 'App\Http\Controllers\Api'], function () {


    Route::prefix('v1')->group(function () {

        Route::group([
            'prefix'    => 'users'
        ], function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\UsersController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\v1\UsersController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\v1\UsersController::class, 'store']);
            Route::post('/{id}', [\App\Http\Controllers\Api\v1\UsersController::class, 'update']);
            Route::get('/edit/{id}', [\App\Http\Controllers\Api\v1\UsersController::class, 'edit']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\v1\UsersController::class, 'destroy']);
        });

        Route::group([
            'prefix'    => 'auto'
        ], function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\AutosController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\v1\AutosController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\v1\AutosController::class, 'store']);
            Route::post('/{id}', [\App\Http\Controllers\Api\v1\AutosController::class, 'update']);
            Route::get('/edit/{id}', [\App\Http\Controllers\Api\v1\AutosController::class, 'edit']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\v1\AutosController::class, 'destroy']);
        });

        Route::group([
            'prefix'    => 'auto-model'
        ], function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\AutoModelsController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\v1\AutoModelsController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\v1\AutoModelsController::class, 'store']);
            Route::post('/{id}', [\App\Http\Controllers\Api\v1\AutoModelsController::class, 'update']);
            Route::get('/edit/{id}', [\App\Http\Controllers\Api\v1\AutoModelsController::class, 'edit']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\v1\AutoModelsController::class, 'destroy']);
        });

        Route::group([
            'prefix'    => 'auto-motor'
        ], function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\AutoMotorsController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\v1\AutoMotorsController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\v1\AutoMotorsController::class, 'store']);
            Route::post('/{id}', [\App\Http\Controllers\Api\v1\AutoMotorsController::class, 'update']);
            Route::get('/edit/{id}', [\App\Http\Controllers\Api\v1\AutoMotorsController::class, 'edit']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\v1\AutoMotorsController::class, 'destroy']);
        });

        Route::group([
            'prefix'    => 'auto-type'
        ], function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\AutoTypesController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\v1\AutoTypesController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\v1\AutoTypesController::class, 'store']);
            Route::post('/{id}', [\App\Http\Controllers\Api\v1\AutoTypesController::class, 'update']);
            Route::get('/edit/{id}', [\App\Http\Controllers\Api\v1\AutoTypesController::class, 'edit']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\v1\AutoTypesController::class, 'destroy']);
        });
    });
});
