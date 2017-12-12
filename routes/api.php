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

Route::group(['middleware' => ['jwt.auth']], function () {
	Route::post('/changepass', 'StudentController@changepass');
	Route::get('/profile', 'ProfileController@index');
	Route::post('/profile-edit', 'ProfileController@profile_edit');
	Route::get('/fetch_question', 'ProfileController@fetch_question');
	Route::post('/getallexam', 'StudentController@get_all_exam');
	Route::post('/getexam', 'StudentController@get_exam_by_exam_id');
	Route::post('/getsubject', 'StudentController@get_subject');
	Route::post('/getarea', 'StudentController@get_area');
	Route::post('/getsection', 'StudentController@get_section');
	Route::post('/getstudymat', 'StudentController@get_studymat');
	Route::post('/adduserexam', 'StudentController@add_user_exam');
});
