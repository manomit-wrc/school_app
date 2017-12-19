@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/banner">Banner</a></li>
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
                <form name="bannerForm" method="POST" action="/banner/add_submit" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Name</label>
                        <div class="col-md-10 {{ $errors->has('banner_name') ? 'has-error' : '' }}">
                            <input type="text" name="banner_name" id="banner_name" class="form-control">
                        </div>
                        @if ($errors->first('banner_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('banner_name') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Image</label>
                        <div class="col-md-10 {{ $errors->has('banner_image') ? 'has-error' : '' }}">
                            <input type="file" name="banner_image" id="banner_image" class="form-control">
                        </div>
                        @if ($errors->first('banner_image'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('banner_image') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            <button type="reset" class="btn btn-sm btn-default">Reset</button>
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
    </script>
@endsection