@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
		<li><a href="javascript:;">Students List</a></li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	
	<!-- end page-header -->

	@if(Session::has('submit-status'))
      	<p class="login-box-msg" style="color: green;">{{ Session::get('submit-status') }}</p>
    @endif

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Students List</h4>
                </div>
                <div class="panel-body">
                    <table id="data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <td>Exam Name</td>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Mobile Number</th>
                                <th>City</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach($fetch_all_student as $key => $value)
                        		
                        		<tr class="odd gradeX">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value['exams']['name'] }}</td>
                                    <td>{{ ucwords($value['first_name'].' '.$value['last_name']) }}</td>
                                    <td>{{ $value['email'] }}</td>
                                    <td>{{ $value['mobile_no'] }}</td>
                                    <td>{{ ucwords($value['city']) }}</td>
                                    <td>

										{{-- <a href="/dashboard/student-delete/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> --}}

                                        <a title="Profile" href="/dashboard/student-profile/{{ $value['id'] }}" class="btn btn-primary btn-sm"><i class="fa fa-user-circle" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                        	@endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>


@endsection