@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/subject">Subject</a></li>
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
            <div class="row">
                <form name="sub_edit_form" method="POST" action="/subject/sub-edit/{{ $subject_details['id'] }}" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Full Name</label>
                        <div class="col-md-10 {{ $errors->has('sub_full_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Subject Full Name" type="text" name="sub_full_name" id="sub_full_name" value="{{ $subject_details['sub_full_name'] }}">
                        </div>
                        @if ($errors->first('sub_full_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_full_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Short Name</label>
                        <div class="col-md-10 {{ $errors->has('sub_short_name') ? 'has-error' : '' }}">
                            <input class="form-control" name="sub_short_name" id="sub_short_name" placeholder="Subject Short Name" type="text" value="{{ $subject_details['sub_short_name'] }}">
                        </div>
                        @if ($errors->first('sub_short_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_short_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course</label>
                        <div class="col-md-10 {{ $errors->has('course') ? 'has-error' : '' }}">
                            <select name="course" id="course" class="form-control">
                                <option value="">Select Course</option>
                                @foreach($fetch_all_course as $key=> $value )
                                    <option value="{{ $value['id'] }}" @if($value['id'] == $subject_details['course_id']) selected="" @endif >{{ ucwords($value['full_name']).' ('.($value['short_name']).')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('course'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Description</label>
                        <div class="col-md-10 {{ $errors->has('sub_description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="course_description" name="sub_description" placeholder="Write your message here..." class="editor form-control">
                                {{$subject_details['sub_desc']}}
                            </textarea>

                        </div>
                        @if ($errors->first('sub_description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Upload File</label>
                        <div class="col-md-10 {{ $errors->has('sub_file') ? 'has-error' : '' }}">
                             @if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png')
                                <a href="{{ url('/upload/subject_file/original/'.$subject_details['sub_file']) }}" target="_blank">

                                    <img src="{{ url('/upload/subject_file/resize/'.$subject_details['sub_file']) }}" class="" style="width: 75px;height: 75px">
                                </a>
                                <br>
                                <br>
                            @elseif($file_extension == 'pdf')
                                <a href="{{ url('/upload/subject_file/others/'.$subject_details['sub_file']) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o" style="font-size:48px;color:red"></i>
                                </a>
                                <br>
                                <br>

                            @elseif($file_extension == 'zip')
                                <a href="{{ url('/upload/subject_file/others/'.$subject_details['sub_file']) }}" target="_blank">
                                    <i class="fa fa-file-zip-o" style="font-size:48px;color:red"></i>
                                </a>
                                <br>
                                <br>

                            @elseif($file_extension == 'mp4')
                                <a href="{{ url('/upload/subject_file/others/'.$subject_details['sub_file']) }}" target="_blank">
                                    <i class="fa fa-video-camera" aria-hidden="true" style="font-size:48px;color:red" title="{{ $subject_details['sub_file'] }}"></i>
                                </a>
                            @endif

                            <input type="hidden" name="existing_file" id="existing_file" class="form-control" value="{{ $subject_details['sub_file'] }}" style="width: 100px;height: 100px">



                            <input type="file" name="sub_file" id="sub_file" class="form-control">
                        </div>
                        @if ($errors->first('sub_file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_file') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            {{-- <button type="reset" class="btn btn-sm btn-default">Cancel</button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->
@endsection