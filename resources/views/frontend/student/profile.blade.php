@extends('dashboard_layout')
<!-- end #header -->

<style type="text/css">
    .error { text-align: left !important; }
</style>

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/students-details">Student</a></li>
            <li class="active">Profile</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->

        <!-- end page-header -->
        <!-- begin profile-container -->
        <div class="profile-container">
            @if(Session::has('submit-status'))
                <p class="login-box-msg" style="color: green;">{{ Session::get('submit-status') }}</p>
            @endif

            @if(Session::has('error-status'))
                <p class="login-box-msg" style="color: red;">{{ Session::get('error-status') }}</p>
            @endif

            <div class="row">

                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#nav-pills-tab-1" data-toggle="tab">
                            <span class="visible-xs">Pills 1</span>
                            <span class="hidden-xs">Information</span>
                        </a>
                    </li>
                    <li>
                        <a href="#nav-pills-tab-2" data-toggle="tab">
                            <span class="visible-xs">Pills 2</span>
                            <span class="hidden-xs">Exam Profile</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="nav-pills-tab-1">
                        <h3 class="m-t-10">Profile</h3>
                        <p>
                            <div class="profile-section">
                                <!-- begin profile-left -->
                                <div class="profile-left">
                                    <!-- begin profile-image -->
                                    <div class="profile-image">
                                        <?php if(empty($fetch_student_details['image'])){?>
                                            <img class="form-group image_preview" width="200" height="175" src="{{ url('/upload/app/profile_image/avatar.png')}}" alt="">
                                        <?php }else{?>
                                            <img class="form-group image_preview" width="200" height="175" src="{{url('upload/app/profile_image/resize/'.$fetch_student_details['image'])}}">

                                            <input class="form-group" type="hidden" name="exiting_profile_image" id="exiting_profile_image" value="{{Auth::guard('admin')->user()->image}}">
                                        <?php } ?>
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
                                                            <h4>{{ ucwords($fetch_student_details['first_name'].' '.$fetch_student_details['last_name']) }}</h4>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="panel-body">
                                        <div class="form-horizontal">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Name</label>
                                                <div class="col-md-9">
                                                    <input readonly="readonly" type="text" class="form-control" placeholder="First Name" name="f_name" value="{{ ucwords($fetch_student_details['first_name'].' '.$fetch_student_details['last_name']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Email</label>
                                                <div class="col-md-9">
                                                    <input readonly="readonly" type="text" class="form-control" placeholder="Last Name" name="l_name" value="{{ $fetch_student_details['email'] }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Mobile</label>
                                                <div class="col-md-9">
                                                    <input readonly="readonly" type="text" class="form-control" placeholder="Mobile" name="mobile" value="{{ $fetch_student_details['mobile_no'] }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Address</label>
                                                <div class="col-md-9">
                                                    <textarea readonly="readonly" class="form-control" placeholder="Address" rows="5" name="address">{{ $fetch_student_details['address'] }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">City</label>
                                                <div class="col-md-9">
                                                    <input readonly="readonly" type="text" class="form-control" placeholder="City" name="mobile" value="{{ $fetch_student_details['city'] }}" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Country</label>
                                                <div class="col-md-9">
                                                    <input  readonly="readonly" type="text" class="form-control" placeholder="Country" name="mobile" value="{{ $fetch_student_details['country'] }}" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Pincode</label>
                                                <div class="col-md-9">
                                                    <input readonly="readonly" type="text" class="form-control" placeholder="Pincode" name="mobile" value="{{ $fetch_student_details['pincode'] }}" />
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
                        </p>
                    </div>
                    <div class="tab-pane fade" id="nav-pills-tab-2">
                        <h3 class="m-t-10">Exam Details</h3>
                        <p>
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Code</label>
                                        <div class="col-md-10 {{ $errors->has('code') ? 'has-error' : '' }}">
                                            <input readonly="readonly" class="form-control" placeholder="Exam Code" type="text" name="code" id="code" value="{{ $fetch_student_details['exams']['code'] }}">
                                        </div>
                                        @if ($errors->first('code'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('code') }}</span>@endif
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-10 {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input readonly="readonly" class="form-control" placeholder="Exam Name" type="text" name="name" id="name" value="{{ $fetch_student_details['exams']['name'] }}">
                                        </div>
                                        @if ($errors->first('name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('name') }}</span>@endif
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Start Date</label>
                                        <div class="col-md-10">
                                            <input readonly="readonly" type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Date" value="{{ $fetch_student_details['exams']['start_date'] }}">
                                        </div>
                                        @if ($errors->first('start_date'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('start_date') }}</span>@endif
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">End Date</label>
                                        <div class="col-md-10 ">
                                            <input readonly="readonly" type="text" name="end_date" id="end_date" class="form-control" placeholder="End Date" value="{{ $fetch_student_details['exams']['end_date'] }}">
                                        </div>

                                        @if ($errors->first('end_date'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('end_date') }}</span>@endif
                                    </div>
                                </div>
                                
                            </div>
                            
                        </p>

                        <h3 class="m-t-10">Marks</h3>
                            <div class="col-md-12">
                                <div class="panel panel-inverse">
                                    <div class="panel-body">
                                            <table id="data-table" class="table table-striped table-bordered" style="font-size: 12px;">
                                                <thead>
                                                    <tr>
                                                        <th>SL NO</th>
                                                        <th>Exam</th>
                                                        <th>Subject</th>
                                                        <th>Area</th>
                                                        <th>Section</th>
                                                        <th>Marks</th>
                                                        <th>Correct Ans.</th>
                                                        <th>Date of Exam</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($user_marks as $key => $value)
                                                        <tr class="odd gradeX">
                                                            <td>{{ ++$key }}</td>
                                                            <td>{{ $value['exams']['name'] }}</td>
                                                            <td>{{ $value['subject']['sub_full_name'] }}</td>
                                                            <td>{{ $value['area']['name'] }}</td>
                                                            <td>{{ $value['section']['name'] }}</td>
                                                            <td>{{ $value['percentile'] }}</td>
                                                            <td>{{ $value['total_correct_ans'] }}</td>
                                                            <td>{{Carbon\Carbon::parse($value['created_at'])->format('d-m-Y')}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        
                                    </div>

                                </div>
                            </div> 
                    </div>
                </div>

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
        #video_sortable, #pdf_sortable, #doc_sortable { width: 50%; float: left; margin-left: 18%; margin-top: 5px; padding: 0; }
        #video_sortable li, #pdf_sortable li, #doc_sortable li { list-style: outside none none; padding: 5px 10px; cursor: move; }
        .div-border { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        input[type="file"] { height: auto; }
    </style>
@endsection