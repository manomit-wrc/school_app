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
});
