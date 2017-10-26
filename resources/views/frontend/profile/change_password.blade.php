@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')
		
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="/">Home</a></li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Change Password</h1>

            @if(Session::has('profile-update'))
              <p class="login-box-msg" style="color: green;">{{ Session::get('profile-update') }}</p>
            @endif

            <!-- end page-header -->
            <!-- begin profile-container -->
            <div class="profile-container">
                <!-- begin profile-section -->
                <form method="POST" action="/profile-submit" name="edit_profile_form" id="edit_profile_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="profile-section">
                        <div class="panel-body">
                            <div class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Old Password</label>
                                    <div class="col-md-9">
                                        <input type="Password" class="form-control" placeholder="Old Password" name="old_password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New Password</label>
                                    <div class="col-md-9">
                                        <input type="Password" class="form-control" placeholder="New Password" name="new_password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Confirm Password</label>
                                    <div class="col-md-9">
                                        <input type="Password" class="form-control" placeholder="Confirm Password" name="confirm_password" />
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
