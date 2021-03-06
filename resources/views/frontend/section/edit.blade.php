@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/area">Area</a></li>
            <li><a href="/area/section/{{ $area_id }}">Section</a></li>
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
                <form name="frmArea" method="POST" action="/area/section/{{ $area_id }}/update/{{ $section->id }}" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Name</label>
                        <div class="col-md-10 {{ $errors->has('name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Section Name" type="text" name="name" id="name" value="{{ $section->name }}">
                        </div>
                        @if ($errors->first('name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('name') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Description</label>
                        <div class="col-md-10 {{ $errors->has('description') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="description" name="description" placeholder="Write your message here..." class="editor form-control">
                                {{ $section->description }}
                            </textarea>

                        </div>
                        @if ($errors->first('description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('description') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            <a href="/section" class="btn btn-sm btn-default">Cancel</a>
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
@endsection