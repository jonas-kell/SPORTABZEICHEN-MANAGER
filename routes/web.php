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

Route::get('api/favourites', [App\Http\Controllers\UsersController::class, 'getFavourites']);
Route::put('api/favourites/add/{id}', [App\Http\Controllers\UsersController::class, 'addFavourite']);
Route::put('api/favourites/drop/{id}', [App\Http\Controllers\UsersController::class, 'dropFavourite']);

Route::post('api/athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
Route::put('api/athlete/update/{id}', [App\Http\Controllers\AthleteController::class, 'update']);
Route::delete('api/athlete/delete/{id}', [App\Http\Controllers\AthleteController::class, 'delete']);
Route::get('api/athlete/{id}', [App\Http\Controllers\AthleteController::class, 'get']);
