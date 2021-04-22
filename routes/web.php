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

Route::get('api/favourites', [App\Http\Controllers\FavouritesController::class, 'get']);
Route::put('api/favourites/add/{id}', [App\Http\Controllers\FavouritesController::class, 'add']);
Route::put('api/favourites/drop/{id}', [App\Http\Controllers\FavouritesController::class, 'drop']);

Route::post('api/athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
Route::put('api/athlete/update/{id}', [App\Http\Controllers\AthleteController::class, 'update']);
Route::delete('api/athlete/delete/{id}', [App\Http\Controllers\AthleteController::class, 'delete']);
Route::get('api/athlete/{id}', [App\Http\Controllers\AthleteController::class, 'get']);
