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
            <li class="active">Add</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        

        <!-- end page-header -->
        <!-- begin profile-container -->
        <div class="profile-container">
            @if(Session::has('submit-status'))
                <p class="login-box-msg" style="color: red;">{{ Session::get('submit-status') }}</p>
            @endif
            <div class="row">
                <form name="topic_form" method="POST" action="/topic/topic-add" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Topic Name</label>
                        <div class="col-md-10 {{ $errors->has('topic_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Topic Name" type="text" name="topic_name" id="topic_name" value="{{ old('topic_name') }}">
                        </div>
                        @if ($errors->first('topic_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('topic_name') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Type</label>
                        <div class="col-md-10 {{ $errors->has('subject_type') ? 'has-error' : '' }}">
                            <select name="subject_type" id="subject_type" class="form-control">
                                <option value="">Select Subject</option>
                                @foreach($fetch_all_subject as $key=> $value )
                                    <option value="{{ $value['id'] }}">{{ ucwords($value['sub_full_name']).' ('.($value['sub_short_name']).')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('subject_type'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject_type') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Topic Description</label>
                        <div class="col-md-10 {{ $errors->has('topic_description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="course_description" name="topic_description" placeholder="Write your message here..." class="editor form-control">
                                {{ old('topic_description') }}
                            </textarea>

                        </div>
                        @if ($errors->first('topic_description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('topic_description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Upload File</label>
                        <div class="col-md-10 {{ $errors->has('topic_file') ? 'has-error' : '' }}">
                            <input type="file" name="topic_file[]" id="topic_file" class="form-control" multiple>
                        </div>

                        @if ($errors->first('topic_file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('topic_file') }}</span>@endif
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
                            <li>Only image files (<strong>JPG, GIF, PNG</strong>) are allowed & pdf,zip files are allowed.</li>
                        </ul>
                    </div>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->
    
@endsection