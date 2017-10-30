<?php

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

Route::get('/', 'AdminController@index');

Route::post('/login-submit','AdminController@login_submit');

Route::group(['middleware' => ['admin']], function() {
	Route::get('/dashboard','DashboardController@index');
	Route::get('/profile-view', 'DashboardController@view_profile');
	Route::post('/profile-submit', 'DashboardController@profile_submit');
	Route::get('/change-password', 'DashboardController@change_password_view');
	Route::get('/logout', 'DashboardController@logout');
	Route::post('/change-password-submit', 'DashboardController@change_password_submit');
	Route::get('/category','CategoryController@index');
	Route::get('/category/add', 'CategoryController@add');
	Route::post('/category/save', 'CategoryController@save');
	Route::get('/category/edit/{id}', 'CategoryController@edit');
	Route::post('/category/update/{id}', 'CategoryController@update');
	Route::get('/category/delete/{id}', 'CategoryController@delete');

	Route::get('/course', 'CourseController@index');
	Route::get('/course/add', 'CourseController@add');
	Route::post('/course/save', 'CourseController@course_save');
	Route::get('/course/edit/{course_id}', 'CourseController@course_edit');
	Route::post('/course/edit-submit/{course_id}', 'CourseController@course_edit_submit');
	Route::get('/course/delete/{course_id}', 'CourseController@course_delete');

	Route::get('/subject', 'SubjectController@index');
	Route::get('/subject/add', 'SubjectController@subject_add');
	Route::post('/subject/sub-add', 'SubjectController@subject_add_save');
	Route::get('/subject/edit/{subject_id}', 'SubjectController@subject_edit');
	Route::post('/subject/sub-edit/{subject_id}', 'SubjectController@subject_edit_save');
	Route::get('/subject/delete/{subject_id}', 'SubjectController@subject_delete');
});
