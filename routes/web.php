<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
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
// Auth::routes();
Route::get('login', [
    'as' => 'login',
    'uses' => 'App\Http\Controllers\Auth\LoginController@showLoginForm'
]);
Route::post('login', [
    'as' => 'login',
    'uses' => 'App\Http\Controllers\Auth\LoginController@login'
]);
Route::post('logout', [
    'as' => 'logout',
    'uses' => 'App\Http\Controllers\Auth\LoginController@logout'
]);

// Password Reset Routes...
Route::post('password/email', [
    'as' => 'password.email',
    'uses' => 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
    'as' => 'password.request',
    'uses' => 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/reset', [
    'as' => 'password.update',
    'uses' => 'App\Http\Controllers\Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
    'as' => 'password.reset',
    'uses' => 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm'
]);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', function () { return redirect(route('home')); });

    Route::resource('/admins', AdminController::class);
});
