@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')
		
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="/dashboard">Home</a></li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Change Password</h1>

            
            <!-- end page-header -->
            <!-- begin profile-container -->
            <div class="profile-container">
                <!-- begin profile-section -->
                <form method="POST" action="/change-password-submit" name="edit_profile_form" id="edit_profile_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="profile-section">
                        <div class="panel-body">
                            @if(Session::has('message'))
                              <div class="alert alert-success" id="success-alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Success! </strong>
                                    {{ Session::get('message') }}
                              </div>
                            @endif

                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Old Password</label>
                                    <div class="col-md-9 {{ $errors->has('old_password') ? 'has-error' : '' }}">
                                        <input type="Password" class="form-control" placeholder="Old Password" name="old_password" value="{{ old('old_password') }}" />
                                        <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New Password</label>
                                    <div class="col-md-9 {{ $errors->has('new_password') ? 'has-error' : '' }}">
                                        <input type="Password" class="form-control" placeholder="New Password" name="new_password" value="{{ old('new_password') }}" />
                                        <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Confirm Password</label>
                                    <div class="col-md-9 {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                                        <input type="Password" class="form-control" placeholder="Confirm Password" name="confirm_password" value="{{ old('confirm_password') }}" />
                                        <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-sm btn-success" id="edit_profile_submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end profile-container -->
        </div>
    @endsection
