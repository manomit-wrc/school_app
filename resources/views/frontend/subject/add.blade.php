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
                <form name="sub_form" method="POST" action="/subject/sub-add" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Full Name</label>
                        <div class="col-md-10 {{ $errors->has('sub_full_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Subject Full Name" type="text" name="sub_full_name" id="sub_full_name" value="{{ old('sub_full_name') }}">
                        </div>
                        @if ($errors->first('sub_full_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_full_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Short Name</label>
                        <div class="col-md-10 {{ $errors->has('sub_short_name') ? 'has-error' : '' }}">
                            <input class="form-control" name="sub_short_name" id="sub_short_name" placeholder="Subject Short Name" type="text" value="{{ old('sub_short_name') }}">
                        </div>
                        @if ($errors->first('sub_short_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_short_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Course</label>
                        <div class="col-md-10 {{ $errors->has('course') ? 'has-error' : '' }}">
                            <select name="course" id="course" class="form-control">
                                <option value="">Select Course</option>
                                @foreach($fetch_all_course as $key=> $value )
                                    <option value="{{ $value['id'] }}">{{ ucwords($value['full_name']).' ('.($value['short_name']).')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('course'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('course') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject Description</label>
                        <div class="col-md-10 {{ $errors->has('sub_description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="course_description" name="sub_description" placeholder="Write your message here..." class="editor form-control">
                                {{ old('sub_description') }}
                            </textarea>

                        </div>
                        @if ($errors->first('sub_description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Upload File</label>
                        <div class="col-md-10 {{ $errors->has('sub_file') ? 'has-error' : '' }}">
                            <input type="file" name="sub_file" id="sub_file" class="form-control">
                        </div>
                        @if ($errors->first('sub_file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('sub_file') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tags</label>
                        <div class="col-md-10 {{ $errors->has('tags') ? 'has-error' : '' }}">
                            <select name="tags[]" id="tags" class="form-control" multiple>
                                @foreach($all_tags as $key=>$value)
                                    <option value="{{ $value['id'] }}">{{ $value['tag_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($errors->first('tags'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('tags') }}</span>@endif
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
    <style type="text/css">
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #49b6d6!important;
            color: #fff!important;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff!important;
            cursor: pointer;
            display: inline-block;
            font-weight: bold;
            margin-right: 2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #333!important;
        }
        
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#tags").select2({
                placeholder: 'Select Tags',
            });
        });
        
    </script>
@endsection