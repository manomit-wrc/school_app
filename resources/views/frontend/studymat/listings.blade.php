@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/question">Study Material</a></li>
	</ol>
	
	<div class="box-footer">
      <a href="/study_mat/add"><button type="button" class="btn btn-success m-r-5 m-b-5">Add Study Material</button></a>
    </div>
    <br>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Study Material</h4>
                </div>
                <div class="panel-body">
                    @if(Session::has('submit-status'))
                        <div class="alert alert-success" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Success! </strong>
                            {{ Session::get('submit-status') }}
                        </div>
                    @endif

                    @if(Session::has('error-status'))
                        <div class="alert alert-danger" id="success-alert">
                            <strong>Error! </strong>
                            {{ Session::get('error-status') }}
                        </div>
                    @endif

                    <table id="data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <th>Subject</th>
                                <th>Area</th>
                                <th>Section</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($fetch_all_study_mat) > 0)
                                <?php $i = 1; ?>
                                @foreach($fetch_all_study_mat as $study_mat)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$study_mat['subject']}}</td>
                                    <td>{{$study_mat['area']}}</td>
                                    <td>{{$study_mat['section']}}</td>
                                    <td>
                                        <a title="Edit" href="/study_mat/edit/{{$study_mat['id']}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>

                                        <a title="Delete" href="/study_mat/delete/{{$study_mat['id']}}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr><td colspan="5">There is no data available</td></tr>
                            @endif
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