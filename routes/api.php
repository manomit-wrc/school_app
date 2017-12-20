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
Route::post('/forgot_password' , 'ProfileController@forgot_password');
Route::post('/otp_verification','ProfileController@otp_verification');
Route::post('/forgot_pw_verification', 'ProfileController@forgot_pw_verification');

Route::group(['middleware' => ['jwt.auth']], function () {
	Route::post('/changepass', 'StudentController@changepass');
	Route::post('/profile', 'ProfileController@index');
	Route::post('/profile-edit', 'ProfileController@profile_edit');
	Route::post('/fetch_question', 'ProfileController@fetch_question');
	Route::post('/changepass', 'StudentController@changepass');
	Route::post('/getallexam', 'ExamController@get_all_exam');
	Route::post('/getsubject', 'SubjectController@get_subject');
	Route::post('/getarea', 'AreaController@get_area');
	Route::post('/getsection', 'SectionController@get_section');
	Route::post('/getstudymat', 'StudyMatController@get_studymat');
	Route::post('/fetch_user_ans', 'ProfileController@fetch_user_ans');
	Route::post('/fetch_question', 'ProfileController@fetch_question');
	Route::post('/getallexam', 'StudentController@get_all_exam');
	Route::post('/getexam', 'StudentController@get_exam_by_exam_id');
	Route::post('/getsubject', 'StudentController@get_subject');
	Route::post('/getarea', 'StudentController@get_area');
	Route::post('/getsection', 'StudentController@get_section');
	Route::post('/getstudymat', 'StudentController@get_studymat');
	Route::post('/adduserexam', 'StudentController@add_user_exam');
	Route::post('/banner','ProfileController@banner');
});
