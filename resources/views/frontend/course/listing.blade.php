@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
		<li><a href="javascript:;">Course</a></li>
	</ol>
	
	<div class="box-footer">
      <a href="/course/add"><button type="button" class="btn btn-success m-r-5 m-b-5">Add Course</button></a>
    </div>
    <br>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Course</h4>
                </div>
                <div class="panel-body">
                    @if(Session::has('submit-status'))
                      <div class="alert alert-success" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Success! </strong>
                            {{ Session::get('submit-status') }}
                      </div>
                    @endif

                    <table id="data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <th>Course Full Name</th>
                                <th>Course Short Name</th>
                                <th>Course Category</th>
                                <th>Course Start Date</th>
                                <th>Course End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course_details as $key => $value)
                                <tr class="odd gradeX">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value['full_name'] }}</td>
                                    <td>{{ $value['short_name'] }}</td>
                                    <td>{{ ucwords($value['category_details']['name']) }}</td>
                                    <td>{{ $value['start_date'] }}</td>
                                    <td>{{ $value['end_date'] }}</td>
                                    <td>
                                        <a title="Edit" href="/course/edit/{{$value['id']}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>

                                        <a title="Delete" href="/course/delete/{{$value['id']}}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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