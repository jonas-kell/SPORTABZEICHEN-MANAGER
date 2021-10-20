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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/get', function () {
        return Auth::user();
    });

    Route::get('year/get', [App\Http\Controllers\UsersController::class, 'getYears']);
    Route::put('year/set', [App\Http\Controllers\UsersController::class, 'setYear']);

    Route::get('search/athletes/{search}', [App\Http\Controllers\SearchController::class, 'getAthletes']);

    Route::get('favourites', [App\Http\Controllers\FavouritesController::class, 'getFavourites']);
    Route::put('favourites/add/{athlete_id}', [App\Http\Controllers\FavouritesController::class, 'addFavourite']);
    Route::put('favourites/drop/{athlete_id}', [App\Http\Controllers\FavouritesController::class, 'dropFavourite']);

    Route::post('athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
    Route::put('athlete/update/{id}', [App\Http\Controllers\AthleteController::class, 'update']);
    Route::put('athlete/update_notes/{id}', [App\Http\Controllers\AthleteController::class, 'updateNotes']);
    Route::put('athlete/update_performances/{id}', [App\Http\Controllers\AthleteController::class, 'updatePerformances']);
    Route::delete('athlete/delete/{id}', [App\Http\Controllers\AthleteController::class, 'delete']);
    Route::get('athlete/{id}', [App\Http\Controllers\AthleteController::class, 'get']);

    Route::put('pdf/generate_from_html', [App\Http\Controllers\PdfGenerationController::class, 'generate']);
});
