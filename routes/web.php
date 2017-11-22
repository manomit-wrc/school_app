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
	Route::get('/subject/topic-add/{subject_id}', 'SubjectController@topic');
	Route::post('/subject/topic_add', 'SubjectController@topic_add');
	Route::post('/subject/topic-upload-post/{subject_id}/{topic_id}/{topic_content_id}', 'SubjectController@topic_upload_post');
	Route::get('/subject/topic-file-delete/{topic_file_id}', 'SubjectController@topic_file_delete');
	Route::get('/subject/topic-add/upload-file/{topic_id}/{topic_content_id}', 'SubjectController@upload_file_view');
	Route::post('/subject/upload_embed_video', 'SubjectController@upload_embed_video');
	Route::post('/subject/upload_dropbox_file', 'SubjectController@upload_dropbox_file');
	Route::post('/subject/topic-add-content', 'SubjectController@topic_add_content');
	Route::get('/subject/topic-content-delete/{subject_id}/{topic_content_id}','SubjectController@topic_content_delete');
	Route::get('/subject/topic-add/topic-content-details/{topic_id}/{topic_content_id}',"SubjectController@topic_content_details");
	Route::get('/subject/content-embedVideo-file-delete/{subject_id}/{embed_video_id}', 'SubjectController@content_embedVideo_delete');
	Route::get('/subject/content-dropbox-file-delete/{subject_id}/{dropbox_file_id}',"SubjectController@content_dropboxFile_delete");
	Route::get('/subject/content-upload-file-delete/{subject_id}/{upload_file_id}',"SubjectController@content_uploadFile_delete");
	Route::post('/subject/fetch_section_name', "SubjectController@fetch_section_name");
	Route::post('/subject/section-name-edit', "SubjectController@section_name_edit");


	Route::get('/topic', 'TopicController@index');
	Route::get('/topic/add', 'TopicController@topic_add');
	Route::post('/topic/topic-add', 'TopicController@topic_add_save');
	Route::get('/topic/delete/{topic_id}', 'TopicController@topic_delete');
	Route::get('/topic/edit/{topic_id}', 'TopicController@topic_edit');
	Route::post('/topic/topic-edit-save/{topic_id}', 'TopicController@topic_edit_save');

	Route::get('/tags', 'TagController@tags_listing');
	Route::get('/tags/add', 'TagController@tags_add');
	Route::post('/tags/tag-add-save', 'TagController@tags_add_save');
	Route::get('/tags/edit/{tag_id}', 'TagController@tags_edit');
	Route::post('/tags/tag-edit-save/{tag_id}', 'TagController@tags_edit_save');
	Route::get('/tags/delete/{tag_id}', 'TagController@tags_delete');
});
