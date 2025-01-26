<?php

declare(strict_types=1);

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserFeedController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (): JsonResponse {
    return response()->json([
        'name'    => 'News Aggregator API',
        'laravel' => [
            'version' => app()->version(),
        ],
        'php'     => [
            'version' => phpversion(),
        ],
    ], 200);
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::apiResource('articles', ArticleController::class)->only('index');
Route::apiResource('categories', CategoryController::class)->only('index');
Route::apiResource('authors', AuthorController::class)->only('index');
Route::apiResource('sources', SourceController::class)->only('index');

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('user-preferences', UserPreferenceController::class);
    Route::apiResource('user-feeds', UserFeedController::class)->only('index');
});
