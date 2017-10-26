@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/category">Category</a></li>
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
                <form name="frmCategory" method="POST" action="/teams" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Parent</label>
                        <div class="col-md-10 {{ $errors->has('p_id') ? 'has-error' : '' }}">
                            <select name="p_id" id="p_id" class="form-control">
                                <option value="0">Top</option>
                            </select>
                        </div>
                        @if ($errors->first('p_id'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('p_id') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Name</label>
                        <div class="col-md-10 {{ $errors->has('name') ? 'has-error' : '' }}">
                            <input class="form-control" name="name" id="name" placeholder="Category Name" type="text" value="{{ old('name') }}">
                        </div>
                        @if ($errors->first('name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('name') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Description</label>
                        <div class="col-md-10 {{ $errors->has('description') ? 'has-error' : '' }}">
                            
                            <textarea id="description" name="description" class="form-control" placeholder="Category description">{{ old('description') }}</textarea>
                        </div>
                        @if ($errors->first('description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('description') }}</span>@endif
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