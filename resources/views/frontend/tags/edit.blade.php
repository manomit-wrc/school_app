@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/tags">Tags</a></li>
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
                <form name="tag_edit_form" method="POST" action="/tags/tag-edit-save/{{ $fetch_tag_details['id'] }}" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tags</label>
                        <div class="col-md-6 {{ $errors->has('tags_name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Tags" type="text" name="tags_name" id="tags_name" value="{{ $fetch_tag_details['tag_name'] }}">
                        </div>
                        @if ($errors->first('tags_name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('tags_name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->
@endsection