<?php

use Illuminate\Http\Request;
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

Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'store']);
Route::post('/login', [\App\Http\Controllers\Api\LoginController::class, 'store']);

Route::middleware('auth:sanctum')->post('/logout',
    [\App\Http\Controllers\Api\LogoutController::class, 'logout']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\UserController::class, 'show']);
    Route::patch('/', [\App\Http\Controllers\Api\UserController::class, 'update']);
});

Route::group(['prefix' => 'films'], function () {
    Route::get('/', [\App\Http\Controllers\Api\FilmController::class, 'index'])->name('films.index'); // all users
    Route::get('/{id}', [\App\Http\Controllers\Api\FilmController::class, 'show'])->name('films.show');                 // all users
    Route::get('/{id}/similar', [\App\Http\Controllers\Api\FilmController::class, 'getSimilar'])      // all users
    ->where('id', '\d+');
    Route::get('/{id}/comments', [\App\Http\Controllers\Api\CommentController::class, 'show'])
        ->name('comments.show')
        ->where('id', '\d+');                                                             // all users
});

Route::prefix('films')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [\App\Http\Controllers\Api\FilmController::class, 'store'])
        ->name('films.store');                                                                      // moderator
    Route::patch('/{id}', [\App\Http\Controllers\Api\FilmController::class, 'update'])                // moderator
        ->where('id', '\d+');
    Route::post('/{id}/favorite', [\App\Http\Controllers\Api\FavoriteController::class, 'store'])
        ->where('id', '\d+');
    Route::delete('/{id}/favorite', [\App\Http\Controllers\Api\FavoriteController::class, 'destroy'])
        ->where('id', '\d+');

    Route::post('/{id}/comments', [\App\Http\Controllers\Api\CommentController::class, 'store'])
        ->where('id', '\d+')->name('comments.store');
});

Route::get('genres/', [\App\Http\Controllers\Api\GenreController::class, 'index'])
    ->name('genres.index');                                                                        // all users

Route::prefix('genres')->middleware('auth:sanctum')->group(function () {
    Route::patch('/{genre}', [\App\Http\Controllers\Api\GenreController::class, 'update'])
        ->name('genres.update');                                                                   // moderator
});

Route::middleware('auth:sanctum')->get('/favorite', [\App\Http\Controllers\Api\FavoriteController::class, 'index']);

Route::prefix('comments')->middleware('auth:sanctum')->group(function () {
    Route::patch('/{comment}', [\App\Http\Controllers\Api\CommentController::class, 'update']);
    Route::delete('/{comment}', [\App\Http\Controllers\Api\CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

Route::prefix('promo')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PromoController::class, 'index']);
    Route::post('/{id}', [\App\Http\Controllers\Api\PromoController::class, 'store'])                // moderator
        ->where('id', '\d+');
});




