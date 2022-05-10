<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\StudentController;
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


Route::post('login', 'App\Http\Controllers\Api\AuthController@login')->name('login');

Route::group([ 'middleware' => ['auth:sanctum'] ], function () {

    // logout
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout')->name('logout');

    Route::resource('/admins', AdminController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/courses', CourseController::class);
    Route::resource('/students', StudentController::class);
    // Route::resource('/enrolls', EnrollController::class);

    // create exam
    Route::post('/exams', 'App\Http\Controllers\Api\ExamController@store');

    // generate code
    Route::get('/enrolls/generate/code/forEnroll/{id}', 'App\Http\Controllers\Api\EnrollController@sendCode');

    // add exam question
    Route::post('/questions/add/in/exam/{id}', 'App\Http\Controllers\Api\ExamQuestionController@store');

    // subadmin operations
    Route::get('/student/degree/{id}', 'App\Http\Controllers\Api\ExamRevisionController@getDegree');
    Route::post('/student/{id}/interview/pass', 'App\Http\Controllers\Api\ExamRevisionController@passInterview');
    Route::post('/student/{id}/interview/failed', 'App\Http\Controllers\Api\ExamRevisionController@failedInterview');

});

// for all other routes
Route::any('{any}', function () {
    return response()->json([
        'message' => 'This Page Is Not Found',
        'status' => 404
    ], 404);
})->where('any', '.*');
