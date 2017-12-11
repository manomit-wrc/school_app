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
	Route::get('/profile', 'ProfileController@index');
	Route::post('/profile-edit', 'ProfileController@profile_edit');
	Route::get('/fetch_question', 'ProfileController@fetch_question');
	Route::post('/changepass', 'StudentController@changepass');
	Route::post('/getallexam', 'ExamController@get_all_exam');
	Route::post('/getsubject', 'SubjectController@get_subject');
	Route::post('/getarea', 'AreaController@get_area');
	Route::post('/getsection', 'SectionController@get_section');
	Route::post('/getstudymats', 'StudyMatController@get_studymat');
});
