<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Start the "API"
Route::get('api/year/get', [App\Http\Controllers\UsersController::class, 'getYears']);
Route::put('api/year/set', [App\Http\Controllers\UsersController::class, 'setYear']);

Route::get('api/search/athletes/{search}', [App\Http\Controllers\SearchController::class, 'getAthletes']);

Route::get('api/favourites', [App\Http\Controllers\FavouritesController::class, 'getFavourites']);
Route::put('api/favourites/add/{athlete_id}', [App\Http\Controllers\FavouritesController::class, 'addFavourite']);
Route::put('api/favourites/drop/{athlete_id}', [App\Http\Controllers\FavouritesController::class, 'dropFavourite']);

Route::post('api/athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
Route::put('api/athlete/update/{id}', [App\Http\Controllers\AthleteController::class, 'update']);
Route::put('api/athlete/update_notes/{id}', [App\Http\Controllers\AthleteController::class, 'updateNotes']);
Route::put('api/athlete/update_performances/{id}', [App\Http\Controllers\AthleteController::class, 'updatePerformances']);
Route::delete('api/athlete/delete/{id}', [App\Http\Controllers\AthleteController::class, 'delete']);
Route::get('api/athlete/{id}', [App\Http\Controllers\AthleteController::class, 'get']);

Route::put('api/pdf/generate_from_html', [App\Http\Controllers\PdfGenerationController::class, 'generate']);
