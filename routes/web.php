<?php

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

Route::get("password/reset/", function () {
    return redirect("/#/auth/password_reset");
})->name("password.reset");

// load the Quasar-SPA (requires the "php artisan storage:link" command)
Route::get('/', function () {
    return \File::get(public_path("frontend/index.html"));
});
