@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/topic">Topic</a></li>
            <li class="active">Edit</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        

        <!-- end page-header -->
        <!-- begin profile-container -->
        <div class="profile-container">
            @if(Session::has('submit-status'))
                <p class="login-box-msg" style="color: red;">{{ Session::get('submit-status') }}</p>
            @endif
            {{-- {{ print_r($errors) }} --}}
            <div class="row">
                <form name="topic_edit_form" method="POST" action="/topic/topic-edit-save/{{ $fetch_topic_details['id'] }}" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Topic Name</label>
                        <div class="col-md-10 {{ $errors->has('topic_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Topic Name" type="text" name="topic_name" id="topic_name" value="{{ $fetch_topic_details['topic_name'] }}">
                        </div>
                        @if ($errors->first('topic_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('topic_name') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Type</label>
                        <div class="col-md-10 {{ $errors->has('subject_type') ? 'has-error' : '' }}">
                            <select name="subject_type" id="subject_type" class="form-control">
                                <option value="">Select Subject</option>
                                @foreach($fetch_all_subject as $key=> $value )
                                    <option value="{{ $value['id'] }}" @if($value['id'] == $fetch_topic_details['subject_details']['id']) selected="" @endif >{{ ucwords($value['sub_full_name']).' ('.($value['sub_short_name']).')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('subject_type'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject_type') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Topic Description</label>
                        <div class="col-md-10 {{ $errors->has('topic_description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="course_description" name="topic_description" placeholder="Write your message here..." class="editor form-control">
                                {{ $fetch_topic_details['topic_description'] }}
                            </textarea>

                        </div>
                        @if ($errors->first('topic_description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('topic_description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Upload File</label>
                        <div class="col-md-10 {{ $errors->has('topic_file.*') ? 'has-error' : '' }}">

                            <?php
                                foreach ($all_uploaded_file as $key => $value) {
                                    $path = $value;
                                    $file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            ?>

                                    @if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png')
                                    <div class="col-md-2" style="margin-bottom: 10px;">
                                        <a href="{{ url('/upload/topic_file/original/'.$all_uploaded_file[$key]) }}" target="_blank">

                                            <img src="{{ url('/upload/topic_file/resize/'.$all_uploaded_file[$key]) }}" class="" style="width: 75px;height: 75px" title="{{ $all_uploaded_file[$key] }}">
                                        </a>
                                    </div>
                                    @elseif($file_extension == 'pdf')
                                    <div class="col-md-2" style="margin-bottom: 10px;">
                                        <a href="{{ url('/upload/topic_file/others/'.$all_uploaded_file[$key]) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o" style="font-size:48px;color:red" title="{{ $all_uploaded_file[$key] }}"></i>
                                        </a>
                                    </div>

                                    @elseif($file_extension == 'zip')
                                    <div class="col-md-2" style="margin-bottom: 10px;">
                                        <a href="{{ url('/upload/topic_file/others/'.$all_uploaded_file[$key]) }}" target="_blank">
                                            <i class="fa fa-file-zip-o" style="font-size:48px;color:red" title="{{ $all_uploaded_file[$key] }}"></i>
                                        </a>
                                    </div>
                                    @endif

                            <?php 
                                }
                            ?>

                            <input type="hidden" name="existing_file" id="existing_file" class="form-control" value="{{ $fetch_topic_details['upload_file'] }}" style="width: 100px;height: 100px">

                            <input type="file" name="topic_file[]" id="topic_file" class="form-control" multiple>
                        </div>

                        @if ($errors->first('topic_file.*'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('topic_file.*') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            {{-- <button type="reset" class="btn btn-sm btn-default">Cancel</button> --}}
                        </div>
                    </div>
                </form>
                <div class="note note-info form-group">
                        <h4>Upload File</h4>
                        <ul>
                            <li>The maximum file size for uploads is <strong>6 MB</strong>.</li>
                            <li>Only image files (<strong>JPG, JPEG, PNG</strong>) are allowed & <strong>pdf,zip</strong> files are allowed.</li>
                        </ul>
                    </div>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->
    
@endsection