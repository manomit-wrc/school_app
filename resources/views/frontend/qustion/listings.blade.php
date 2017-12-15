@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/question">Question</a></li>
	</ol>
	
	<div class="box-footer">
      <a href="/question/add"><button type="button" class="btn btn-success m-r-5 m-b-5">Add Question</button></a>
    </div>
    <br>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Question</h4>
                </div>
                <div class="panel-body">
                    @if(Session::has('submit-status'))
                      <div class="alert alert-success" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Success! </strong>
                            {{ Session::get('submit-status') }}
                      </div>
                    @endif
                    <a href="/question" class="pull-right"><button type="button" class="btn btn-success m-r-5 m-b-5">Reset</button></a>
                    <a href="/question/search" class="pull-right"><button type="button" class="btn btn-success m-r-5 m-b-5">Filter By</button></a>
                    <table id="data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Subject</th>
                                <th>Exams</th>
                                <th>Area</th>
                                <th>Section</th>
                                <th>Level</th>
                                <th>Question</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fetch_all_question as $key => $value)
                                <tr class="odd gradeX">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value['subject']['sub_full_name'] }}</td>
                                    <td>{{ $value['exam'] }}</td>
                                    <td>{{ $value['area']['name'] }}</td>
                                    <td>{{ $value['section']['name'] }}</td>
                                    <td>
                                        @if($value['level'] == '1')
                                            {{ 'Level 1' }}
                                        @elseif($value['level'] == '2')
                                            {{ 'Level 2' }}
                                        @elseif($value['level'] == '3')
                                            {{ 'Level 3' }}
                                        @elseif($value['level'] == '4')
                                            {{ 'Level 4' }}
                                        @elseif($value['level'] == '5')
                                            {{ 'Level 5' }}
                                        @endif
                                    </td>
                                    <td>{{ strip_tags($value['question']) }}</td>
                                    <td>
                                        <a title="Edit" href="/question/edit/{{ $value['id'] }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>

                                        <a title="Delete" href="/question/delete/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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