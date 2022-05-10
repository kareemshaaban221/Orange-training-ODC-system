<?php

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

Route::post('login','App\Http\Controllers\AuthController@login')->name('login');
Route::post('register','App\Http\Controllers\AuthController@register')->name('register');

Route::get('/courses', 'App\Http\Controllers\CourseController@index');

Route::group([ 'middleware' => ['auth:sanctum'] ], function () {

    Route::post('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

    // student routes
    Route::get('/profile', 'App\Http\Controllers\ProfileController@index');
    Route::get('/profile/status', 'App\Http\Controllers\ProfileController@getStatusMessage');
    Route::post('/profile/update', 'App\Http\Controllers\ProfileController@update');
    Route::post('/profile/password/update', 'App\Http\Controllers\ProfileController@updatePassword');

    // course routes
    Route::post('/course/enroll/{course_name}', 'App\Http\Controllers\CourseController@enroll');

    // exam routes
    Route::get('/course/enroll/getcode', 'App\Http\Controllers\ExamController@getExamCode');
    Route::get('/exam/get/{code}', 'App\Http\Controllers\ExamController@getExam');
    Route::post('/exam/submit', 'App\Http\Controllers\ExamController@submit');

});

// for all other routes
Route::any('{any}', function () {
    return response()->json([
        'message' => 'This Page Is Not Found',
        'status' => 404
    ], 404);
})->where('any', '.*');
