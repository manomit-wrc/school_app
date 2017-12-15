@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
		<li><a href="javascript:;">Subject</a></li>
	</ol>
	
	<div class="box-footer">
      <a href="/subject/add"><button type="button" class="btn btn-success m-r-5 m-b-5">Add Subject</button></a>
    </div>
    <br>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Subject</h4>
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
                                <th>Subject Full Name</th>
                                <th>Subject Short Name</th>
                                <th>Exam Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fetch_all_subject as $key => $value)
                                <tr class="odd gradeX">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value['sub_full_name'] }}</td>
                                    <td>{{ $value['sub_short_name'] }}</td>
                                    <td>{{ $value['exam_names'] }}</td>
                                    <td>
                                        <a title="Edit" href="/subject/edit/{{$value['id']}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>

                                        <a title="Delete" href="/subject/delete/{{$value['id']}}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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