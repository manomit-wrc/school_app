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
      <a href="/exam_question/add"><button type="button" class="btn btn-success m-r-5 m-b-5">Add Exam Question</button></a>
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
                    <a class="pull-right filter-div"><button type="button" class="btn btn-success m-r-5 m-b-5">Filter By</button></a>
                    <div id="filter-panel" @if(isset($exam)) style="display: block;" @endif>
                        <form exam="frmArea" method="POST" action="/question/filter-submit" class="form-horizontal">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-2 control-label">Exam</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="exam" id="exam">
                                        <option value="0">Select Exam</option>
                                        @foreach($fetch_all_exam as $key => $value)
                                            <option value="{{ $key }}" @if(isset($exam) && $key == $exam) selected="selected" @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-10 text-left">
                                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                    <table id="data-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Exams</th>
                                <th>Question</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fetch_all_question as $key => $value)
                                <tr class="odd gradeX">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value['exam'] }}</td>
                                    <td>{{ strip_tags($value['question']) }}</td>
                                    <td>
                                        <a title="Edit" href="/exam_question/edit/{{ $value['id'] }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>

                                        <a title="Delete" href="/exam_question/delete/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
<style type="text/css">
    #filter-panel {
        padding: 5px;
        text-align: center;
        border: 1px solid #ccd0d4;
        border-radius: 5px;
    }

    #filter-panel {
        padding: 50px;
        display: none;
        margin-top: 40px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('.filter-div').on('click', function () {
            $("#filter-panel").toggle();
        });
    });
</script>
@endsection