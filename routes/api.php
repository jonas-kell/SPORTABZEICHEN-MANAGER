<?php

use App\Http\Middleware\CacheMiddleware;
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

Route::get('user/get', function () {
    return Auth::user() ?? "null";
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('year/get', [App\Http\Controllers\UsersController::class, 'getYears']);
    Route::put('year/set', [App\Http\Controllers\UsersController::class, 'setYear']);

    Route::get('search/athletes/{search}', [App\Http\Controllers\SearchController::class, 'getAthletes'])->middleware([CacheMiddleware::class]);

    Route::get('favourites', [App\Http\Controllers\FavouritesController::class, 'getFavourites'])->middleware([CacheMiddleware::class]);
    Route::put('favourites/add/{athlete_id}', [App\Http\Controllers\FavouritesController::class, 'addFavourite']);
    Route::put('favourites/drop/{athlete_id}', [App\Http\Controllers\FavouritesController::class, 'dropFavourite']);

    Route::get('relevant_athletes/{year}/{requirements?}', [App\Http\Controllers\AthleteController::class, 'getRelevantAthletes'])->middleware([CacheMiddleware::class]);

    Route::post('athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
    Route::put('athlete/update/{id}', [App\Http\Controllers\AthleteController::class, 'update']);
    Route::put('athlete/update_notes/{id}', [App\Http\Controllers\AthleteController::class, 'updateNotes']);
    Route::put('athlete/update_performances/{id}', [App\Http\Controllers\AthleteController::class, 'updatePerformances']);
    Route::put('athlete/update_swimming_year/{id}', [App\Http\Controllers\AthleteController::class, 'updateSwimmingYear']);
    Route::delete('athlete/delete/{id}', [App\Http\Controllers\AthleteController::class, 'delete']);
    Route::get('athlete/{id}', [App\Http\Controllers\AthleteController::class, 'get'])->middleware([CacheMiddleware::class]);

    Route::middleware('throttle:1,0.1')->group(function () {
        Route::put('pdf/generate_table_from_html', [App\Http\Controllers\PdfGenerationController::class, 'generateTable']);
    });
    Route::middleware('throttle:1,0.1')->group(function () {
        Route::put('pdf/generate_output_pdf', [App\Http\Controllers\PdfGenerationController::class, 'generateOutput']);
    });

    Route::put('export/json', [App\Http\Controllers\ExportController::class, 'json']);

    Route::get('cache_element/{uuid_array}', [App\Http\Controllers\CacheController::class, 'getCacheElements']);
});
