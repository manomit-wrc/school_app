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
                <form name="course_form" method="POST" action="/course/save" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Full Name</label>
                        <div class="col-md-10 {{ $errors->has('course_full_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Course Full Name" type="text" name="course_full_name" id="course_full_name" value="{{ old('course_full_name') }}">
                        </div>
                        @if ($errors->first('course_full_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_full_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Short Name</label>
                        <div class="col-md-10 {{ $errors->has('course_short_name') ? 'has-error' : '' }}">
                            <input class="form-control" name="course_short_name" id="course_short_name" placeholder="Course Short Name" type="text" value="{{ old('course_short_name') }}">
                        </div>
                        @if ($errors->first('course_short_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_short_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Category</label>
                        <div class="col-md-10 {{ $errors->has('course_category') ? 'has-error' : '' }}">
                            <select name="course_category" id="course_category" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($all_categories as  $value )
                                    <option value="{{ $value['id'] }}">{{ ucwords($value['name']) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('course_category'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_category') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Description</label>
                        <div class="col-md-10 {{ $errors->has('course_description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="course_description" name="course_description" placeholder="Write your message here..." class="editor form-control">
                                {{ old('course_description') }}
                            </textarea>

                        </div>
                        @if ($errors->first('course_description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course_description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Upload File</label>
                        <div class="col-md-10 {{ $errors->has('file') ? 'has-error' : '' }}">
                            <input type="file" name="file" id="file" class="form-control">
                        </div>
                        @if ($errors->first('file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('file') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Start Date</label>
                        <div class="input-group col-md-10 date {{ $errors->has('start_date') ? 'has-error' : '' }}" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                            <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Date" value="{{ old('start_date') }}">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                        @if ($errors->first('start_date'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('start_date') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">End Date</label>
                        <div class="input-group col-md-10 date {{ $errors->has('end_date') ? 'has-error' : '' }}" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                            <input type="text" name="end_date" id="end_date" class="form-control" placeholder="End Date" value="{{ old('end_date') }}">
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