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
	Route::post('/subject/section-delete', "SubjectController@section_delete");


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

	Route::get('/exam','ExamController@index');
	Route::get('/exam/add','ExamController@add');
	Route::post('/exam/save', 'ExamController@save');
	Route::get('/exam/edit/{id}','ExamController@edit');
	Route::post('/exam/update/{id}', 'ExamController@update');
	Route::get('/exam/delete/{id}','ExamController@delete');

	Route::get('/area','AreaController@index');
	Route::get('/area/add','AreaController@add');
	Route::post('/area/get-subject-by-exam', 'AreaController@get_subject_by_exam');
	Route::post('/area/save','AreaController@save');
	Route::get('/area/edit/{id}','AreaController@edit');
	Route::post('/area/update/{id}','AreaController@update');
	Route::get('/area/delete/{id}','AreaController@delete');

	Route::get('/area/section/{area_id}','SectionController@index');
	Route::get('/area/section/{area_id}/add','SectionController@add');
	Route::post('/area/section/{area_id}/save','SectionController@save');
	Route::get('/area/section/{area_id}/edit/{id}','SectionController@edit');
	Route::post('/area/section/{area_id}/update/{id}','SectionController@update');
	Route::get('/area/section/{area_id}/delete/{id}','SectionController@delete');

	Route::get('/question', 'AddQuestionController@index');
	Route::match(array('GET', 'POST'), '/question/add','AddQuestionController@add_qustion_view');
	Route::post('/question/fetch-exam-subject-wise', 'AddQuestionController@fetch_exam_subject_wise');
	Route::post('/question/fetch-area-exam-wise', 'AddQuestionController@fetch_area_exam_wise');
	Route::post('/question/fetch-section-area-wise', 'AddQuestionController@fetch_section_area_wise');
	Route::post('/question/add-question-submit', 'AddQuestionController@add_qustion_submit');

	Route::get('/study_mat', 'StudyMatController@index');
	Route::match(array('GET', 'POST'), '/study_mat/add', 'StudyMatController@add_study_mat_view');
	Route::post('/study_mat/fetch-subject-wise-area', 'StudyMatController@fetch_subject_wise_area');
	Route::post('/study_mat/fetch-area-wise-section', 'StudyMatController@fetch_area_wise_section');
	Route::post('/study_mat/add-study-mat-submit', 'StudyMatController@study_mat_submit');
});
