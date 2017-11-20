@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
		<li><a href="/subject">Subject</a></li>
        <li><a href="/subject/topic-add/{{ $fetch_section_details['subject_id'] }}">{{ $fetch_section_details['topic_name'] }}</a></li>
	</ol>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-stuff-1" data-init="true">
                <div class="panel-heading">
                    <h4 class="panel-title">Upload Files</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" name="topic_contyect_add_form" method="POST" action="/subject/topic-upload-post/{{ $fetch_section_details['subject_id'] }}/{{ $fetch_topic_content_details['topic_id'] }}/{{ $fetch_topic_content_details['id'] }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label">Title</label>
                            <div class="col-md-4 {{ $errors->has('title') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Title" name="title" id="title" value="{{ $fetch_topic_content_details['title'] }}">

                                @if ($errors->first('title'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('title') }}</span>@endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Service</label>
                            <div class="col-md-4">
                                <label class="radio-inline">
                                    <input type="radio" name="optionService" value="1" @if($fetch_topic_content_details['service_type'] == 1) checked="" @endif>
                                    Paid
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optionService" value="2" @if($fetch_topic_content_details['service_type'] == 2) checked="" @endif>
                                    Trail
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Upload File</label>
                            <div class="col-md-4">
                                <input type="file" class="form-control" name="file[]" id="file_chooser" accept="image/gif, image/jpeg, image/jpg, image/png, image/bmp, .mp4, .pdf" multiple="multiple" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                            </div>
                        </div>
                    </form>

                        <div class="form-group">
                            {{-- <div class="col-md-3" style="padding: 20px 10px 20px 35px;">
                                <center>
                                <form id="upload_section_file" name="upload_section_file" method="POST" enctype="multipart/form-data">      
                                  <input type="file" style="display:block" id="file_chooser" accept="image/gif, image/jpeg, image/jpg, image/png, image/bmp, .mp4, .pdf" multiple="multiple" name="file[]" >
                                  <div class="upload-item-panel" id="uploadFromComputer">
                                    <img class="upload-item-img" src="https://www.mathoratory.com/styles/images/upload-cloud.png">
                                    <p class="upload-item-lable">Upload video, pdf, images from your computer</p>
                                  </div>
                                </form>
                                </center>
                            </div> --}}
                            <div class="col-md-3" style="padding: 20px 10px;">
                                <center>
                                  <div class="upload-item-panel" id="uploadEmbedVideo">
                                    <img class="upload-item-img" src="https://www.mathoratory.com/styles/images/upload-embed.png">
                                    <p class="upload-item-lable">
                                        <a href="#modal_embed_video" class="btn btn-sm btn-success m-r-5 m-b-5" data-toggle="modal">Embed video from youtube</a>
                                        <br>
                                    </p>
                                  </div>
                                </center>
                            </div>
                            <div class="col-md-3" style="padding: 20px 10px;">
                                <center>
                                  <div class="upload-item-panel" id="syncFromDropbox">
                                    <img class="upload-item-img" src="https://www.mathoratory.com/styles/images/upload-dropbox.png">
                                    <p class="upload-item-lable">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success m-r-5 m-b-5 dropbox_file" topic_content_id="{{ $fetch_topic_content_details['id'] }}">Sync video, pdf, images from Dropbox</a>
                                        <br>
                                    </p>
                                  </div>
                                </center>
                            </div>
                        </div>
{{-- 
                        <div class="form-group" id="show_embed_video">
                            
                        </div> --}}

                    
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<div class="modal fade" id="modal_embed_video" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Embed Videos</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <form action="javascript:void(0)" id="embed_video_add_form" name="embed_video_add_form">
                    {{-- <input type="hidden" name="subject_id" id="subject_id" value=""> --}}
                        <fieldset>
                            <div style="text-align: center; font-size: 16px; padding: 5px; color: hsl(0, 0%, 52%);">
                                We support <b>Youtube</b> video embedding.
                            </div>

                            <div class="form-group" style="margin-top: 20px;">
                                <div class="input-group" id="embedForm">
                                  <span class="input-group-addon" style="color:hsl(198, 83%, 56%);font-weight:bold;font-size:16px;">URL</span>
                                  <input type="text" id="embedUrl" name="embedUrl" class="form-control" placeholder="youtube url">
                                </div>
                                <div id="embedUrl-status-msg" style="margin-top: 10px; color:#F56161; display: none"></div>

                                <label id="embedUrl-error" class="error" for="embedUrl"></label>
                            </div>

                            <br/>
                            <br/>
                        </fieldset>

                        <div class="modal-footer"">
                            <button type="submit" class="btn btn-sm btn-success" id="embedd_video_submit" topic_content_id="{{ $fetch_topic_content_details['id'] }}">Embed</button>
                            <button type="button" class="btn btn-sm btn-white" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection