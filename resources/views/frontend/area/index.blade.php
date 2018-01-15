@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
		<li><a href="javascript:;">Area</a></li>
	</ol>
	
	<div class="box-footer">
      <a href="/area/add"><button type="button" class="btn btn-success m-r-5 m-b-5">Add Area</button></a>
    </div>
    <br>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Area</h4>
                </div>
                <div class="panel-body">
                    @if(Session::has('submit-status'))
                      <div class="alert alert-success" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Success! </strong>
                            {{ Session::get('submit-status') }}
                      </div>
                    @endif

                    <a href="/area" class="pull-right"><button type="button" class="btn btn-success m-r-5 m-b-5">Reset</button></a>
                    <a class="pull-right filter-div"><button type="button" class="btn btn-success m-r-5 m-b-5">Filter By</button></a>
                    @if(isset($subject) || isset($exam))<a class="pull-right"><button type="button" class="btn btn-success m-r-5 m-b-5" data-toggle="modal" data-target="#reorderModal">Reorder</button></a>@endif
                    <div id="filter-panel" @if(isset($subject) || isset($exam)) style="display: block;" @endif>
                        <form exam="frmSearch" method="POST" action="/area/filter-submit" class="form-horizontal">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-2 control-label">Subject</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="subject" id="subject">
                                        <option value="">Select Subject</option>
                                        @foreach($fetch_all_subject as $key => $value)
                                            <option value="{{ $key }}" @if(isset($subject) && $key == $subject) selected="selected" @endif>{{ $value }}</option>
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
                                <th>SL NO</th>
                                <th>Name</th>
                                <th>Exam</th>
                                <th>Subject</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($areas as $key => $value)
                                <tr class="odd gradeX">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['exam'] }}</td>
                                    <td>{{ $value['subjects']['sub_full_name'] }}</td>
                                    <td>
                                        <a title="Edit" href="/area/edit/{{$value['id']}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>

                                        <a title="Delete" href="/area/delete/{{$value['id']}}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>

                                        <a title=" View Section" href="/area/section/{{$value['id']}}" class="btn btn-primary btn-sm"><i class="fa fa-briefcase"></i></a>
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

<!-- Starts Reorder Modal -->
<div class="modal fade" id="reorderModal" tabindex="-1" role="dialog" aria-labelledby="reorderModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="reorderModalLabel">Reorder</h4>
      </div>
      <div class="modal-body">
        <ul id="area_sortable" class="ui-sortable">
            @foreach($areas as $key => $value)
            <li class="ui-state-default area_li">
                <input type="hidden" name="area_id" value="{{ $value['id'] }}" />
                {{ $value['name'] }}
            </li>
            @endforeach
        </ul>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="area_sort_btn">Go</button>
      </div>
    </div>
  </div>
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

    #area_sortable li { list-style: outside none none; padding: 5px 10px; cursor: move; }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('.filter-div').on('click', function () {
            $("#filter-panel").toggle();
        });

        $( "#area_sortable" ).sortable();
        $( "#area_sortable" ).disableSelection();

        var formdata = new FormData();
        var area_order = [];
        var area_id = [];
        $('#area_sort_btn').on('click', function (e) {
            $(".area_li").each(function(index) {
                formdata.append('_token', '{{csrf_token()}}');
                formdata.append('area_id[]', $(this).children('input').val());
                formdata.append('area_order[]', $(this).text());
            });

            $.ajax({
                type: "POST",
                url: '/area/sort-order-update',
                processData: false,
                contentType: false,
                data: formdata,
                success: function (data) {
                    if (data == 1) {
                        window.location.href = '/area/';
                    } else {
                        alert('error');
                    }
                }
            });
            e.preventDefault();
        });
    });
</script>
@endsection