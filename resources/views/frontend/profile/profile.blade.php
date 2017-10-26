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
            <h1 class="page-header">Profile</h1>

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
                        <!-- begin profile-left -->
                        <div class="profile-left">
                            <!-- begin profile-image -->
                            <div class="profile-image">
                                <?php if(empty(Auth::guard('admin')->user()->image)){?>
                                    <img class="form-group image_preview" width="200" height="175" src="{{ url('/upload/profile_image/default.png')}}" alt="">
                                <?php }else{?>
                                    <img class="form-group image_preview" width="200" height="175" src="{{url('upload/profile_image/resize/'.Auth::guard('admin')->user()->image)}}">

                                    <input class="form-group" type="hidden" name="exiting_profile_image" id="exiting_profile_image" value="{{Auth::guard('admin')->user()->image}}">
                                <?php } ?>
                            </div>
                            <!-- end profile-image -->
                            <div class="m-b-10">
                                <input type="file" name="profile_image" class="profile_image">
                                <span class="text-danger">{{ $errors->first('profile_image') }}</span>
                            </div>
                        </div>
                        <!-- end profile-left -->
                        <!-- begin profile-right -->
                        <div class="profile-right">
                            <!-- begin profile-info -->
                            <div class="profile-info">
                                <!-- begin table -->
                                <div class="table-responsive">
                                    <table class="table table-profile">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>
                                                    <h4>{{ Auth::guard('admin')->user()->first_name. ' '.Auth::guard('admin')->user()->last_name }}<small>Admin</small></h4>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="panel-body">
                                <div class="form-horizontal">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">First Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="First Name" name="f_name" value="{{ Auth::guard('admin')->user()->first_name }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Last Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Last Name" name="l_name" value="{{ Auth::guard('admin')->user()->last_name }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mobile</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Mobile" name="mobile" value="{{ Auth::guard('admin')->user()->mobile }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ Auth::guard('admin')->user()->email }}" disabled />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Address</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" placeholder="Address" rows="5" name="address">{{ Auth::guard('admin')->user()->address }}</textarea>
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
                                <!-- end table -->
                            </div>
                            <!-- end profile-info -->
                        </div>
                        <!-- end profile-right -->
                    </div>
                </form>
            </div>
            <!-- end profile-container -->
        </div>
    @endsection
