<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/registration', 'StudentController@registration');
Route::post('/login', 'StudentController@login');

Route::group(['middleware' => ['jwt.auth']], function (){
	Route::get('/profile', 'ProfileController@index');
	Route::post('/profile-edit', 'ProfileController@profile_edit');
});
