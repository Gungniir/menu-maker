<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MenuSchemeController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api'
], static function () {
    Route::apiResource('dish', DishController::class);
    Route::prefix('dish')->group(static function () {
        Route::put('{dish}/ingredient/{ingredient}', [DishController::class, 'storeIngredient']);
        Route::delete('{dish}/ingredient/{ingredient}', [DishController::class, 'destroyIngredient']);
        Route::post('{dish}/recipe', [DishController::class, 'storeRecipeItem']);
        Route::put('{dish}/recipe/{recipeItem}', [DishController::class, 'updateRecipeItem']);
        Route::delete('{dish}/recipe/{recipeItem}', [DishController::class, 'destroyRecipeItem']);
        Route::put('{dish}/image/{image}', [DishController::class, 'attachImage']);
        Route::delete('{dish}/image/{image}', [DishController::class, 'detachImage']);
    });

    Route::apiResource('user', UserController::class);
    Route::apiResource('image', ImageController::class, [
        'except' => ['update']
    ]);
    Route::apiResource('tool', ToolController::class);
    Route::apiResource('ingredient', IngredientController::class);
    Route::apiResource('scheme', MenuSchemeController::class);
});
