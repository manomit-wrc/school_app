@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/course">Course</a></li>
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
                <form name="course_edit_form" method="POST" action="/course/edit-submit/{{ $fetch_course['id'] }}" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Full Name</label>
                        <div class="col-md-10 {{ $errors->has('course_full_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Course Full Name" type="text" name="course_full_name" id="course_full_name" value="{{ $fetch_course['full_name'] }}">
                        </div>
                        @if ($errors->first('course_full_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_full_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Short Name</label>
                        <div class="col-md-10 {{ $errors->has('course_short_name') ? 'has-error' : '' }}">
                            <input class="form-control" name="course_short_name" id="course_short_name" placeholder="Course Short Name" type="text" value="{{ $fetch_course['short_name'] }}">
                        </div>
                        @if ($errors->first('course_short_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_short_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Category</label>
                        <div class="col-md-10 {{ $errors->has('course_category') ? 'has-error' : '' }}">
                            <select name="course_category" id="course_category" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($all_categories as  $value )
                                    <option value="{{ $value['id'] }}" @if($value['id'] == $fetch_course['category_details']['id']) selected="" @endif>{{ ucwords($value['name']) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('course_category'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_category') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Description</label>
                        <div class="col-md-10 {{ $errors->has('course_description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="course_description" name="course_description" placeholder="Write your message here..." class="editor form-control">
                                {{ $fetch_course['description'] }}
                            </textarea>

                        </div>
                        @if ($errors->first('course_description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Upload File</label>
                        <div class="col-md-10 {{ $errors->has('file') ? 'has-error' : '' }}">
                            @if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png')
                                <a href="{{ url('/upload/course_file/original/'.$fetch_course['description_file']) }}" target="_blank">

                                    <img src="{{ url('/upload/course_file/resize/'.$fetch_course['description_file']) }}" class="" style="width: 75px;height: 75px">
                                </a>
                                <br>
                                <br>
                            @elseif($file_extension == 'pdf')
                                <a href="{{ url('/upload/course_file/others/'.$fetch_course['description_file']) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o" style="font-size:48px;color:red"></i>
                                </a>
                                <br>
                                <br>

                            @elseif($file_extension == 'zip')
                                <a href="{{ url('/upload/course_file/others/'.$fetch_course['description_file']) }}" target="_blank">
                                    <i class="fa fa-file-zip-o" style="font-size:48px;color:red"></i>
                                </a>
                                <br>
                                <br>
                            @elseif($file_extension == 'mp4')
                                <a href="{{ url('/upload/course_file/others/'.$fetch_course['description_file']) }}" target="_blank">
                                    <i class="fa fa-video-camera" aria-hidden="true" style="font-size:48px;color:red" title="{{ $fetch_course['description_file'] }}"></i>
                                </a>
                            @endif

                            <input type="hidden" name="existing_file" id="existing_file" class="form-control" value="{{ $fetch_course['description_file'] }}" style="width: 100px;height: 100px">

                            <input type="file" name="file" id="file" class="form-control">
                        </div>
                        @if ($errors->first('file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('file') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Start Date</label>
                        <div class="input-group col-md-10 date {{ $errors->has('start_date') ? 'has-error' : '' }}" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                            <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Date" value="{{ $fetch_course['start_date'] }}">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                        @if ($errors->first('start_date'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('start_date') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">End Date</label>
                        <div class="input-group col-md-10 date {{ $errors->has('end_date') ? 'has-error' : '' }}" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                            <input type="text" name="end_date" id="end_date" class="form-control" placeholder="End Date" value="{{ $fetch_course['end_date'] }}">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>

                        @if ($errors->first('end_date'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('end_date') }}</span>@endif
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