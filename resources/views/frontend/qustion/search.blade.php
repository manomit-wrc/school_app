@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/question">Question</a></li>
        <li class="active">Filter</a></li>
	</ol>
	
    <!-- begin profile-container -->
    <div class="profile-container">
    	<!-- begin row -->
    	<div class="row">
            <form exam="frmArea" method="POST" action="/question/filter-submit" class="form-horizontal">
                {{ csrf_field() }}
                
                <div class="form-group">
                    <label class="col-md-2 control-label">Subject</label>
                    <div class="col-md-10">
                        <select class="form-control" name="subject" id="subject">
                            <option value="">Select Subject</option>
                            @foreach($fetch_all_subject as $key => $value)
                                <option value="{{ $key }}" subject_name="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Exam</label>
                    <div class="col-md-10">
                        <select class="form-control" name="exam" id="exam">
                            <option value="">Select Exam</option>
                            @foreach($fetch_all_exam as $key => $value)
                                <option value="{{ $key }}" exam_name="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Area</label>
                    <div class="col-md-10">
                        <select class="form-control" name="area" id="area">
                            <option value="">Select Area</option>
                            @foreach($fetch_all_area as $key => $value)
                                <option value="{{ $key }}" area_name="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Section</label>
                    <div class="col-md-10">
                        <select class="form-control" name="section" id="section">
                            <option value="">Select Section</option>
                            @foreach($fetch_all_section as $key => $value)
                                <option value="{{ $key }}" section_name="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Level</label>
                    <div class="col-md-10">
                        <select class="form-control" name="level" id="level">
                            <option value="">Select Level</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                            <option value="3">Level 3</option>
                            <option value="4">Level 4</option>
                            <option value="5">Level 5</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-4 col-md-offset-2">
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        <button type="reset" class="btn btn-sm btn-default" id="reset_form">Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- end row -->
    </div>
    <!-- end profile-container -->
</div>
@endsection