@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/tips">Tips</a></li>
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
                <form name="tipsForm" method="POST" action="/tips/add_submit" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Title</label>
                        <div class="col-md-10 {{ $errors->has('tips_title') ? 'has-error' : '' }}">
                            <input type="text" name="tips_title" id="tips_title" class="form-control">
                        </div>
                        @if ($errors->first('tips_title'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('tips_title') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Description</label>
                        <div class="col-md-10 {{ $errors->has('tips_desc') ? 'has-error' : '' }}">
                            <textarea name="tips_desc" id="tips_desc" class="form-control"></textarea>
                        </div>
                        @if ($errors->first('tips_desc'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('tips_desc') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            <a href="/tips" class="btn btn-sm btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->
@endsection