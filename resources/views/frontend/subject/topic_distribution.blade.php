@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/subject">Subject</a></li>
		<li><a href="javascript:;">Topic Distribution</a></li>
	</ol>

    <div class="box-footer">
      <a href="#modal-topic-add" class="btn btn-sm btn-success m-r-5 m-b-5" data-toggle="modal">Add Topic</a>
    </div>
    <br>

    @if(Session::has('submit-status'))
      <div class="alert alert-success" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>Success! </strong>
            {{ Session::get('submit-status') }}
      </div>
    @endif

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
            <div class="panel-group" id="accordion">
                @for($i=0; $i<count($fetch_all_topic); $i++)
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $i }}">
                                    <i class="fa fa-plus-circle pull-right"></i> 
                                    {{ $fetch_all_topic[$i]['topic_name'] }}
                                </a>
                            </h3>
                        </div>
                        <div id="collapse{{ $i }}" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                        <?php
                                        if(!empty($fetch_all_topic[$i]['topic_files'])){
                                            foreach ($fetch_all_topic[$i]['topic_files'] as $key => $value) {
                                                $path = $value['upload_file'];
                                                $file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        ?>
                                                @if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png')

                                                    <tr>
                                                        <td>
                                                            <a href="{{ url('/upload/topic_file/original/'.$value['upload_file']) }}" target="_blank">

                                                                {{ $value['upload_file'] }}
                                                            </a>

                                                            <span class="pull-right">
                                                                <a title="Delete" href="/subject/topic-file-delete/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                            </span>
                                                        </td>
                                                    </tr>

                                                @else

                                                    <tr>
                                                        <td>
                                                            <a href="{{ url('/upload/topic_file/others/'.$value['upload_file']) }}" target="_blank">

                                                                {{ $value['upload_file'] }}
                                                            </a>

                                                            <span class="pull-right">
                                                                <a title="Delete" href="/subject/topic-file-delete/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                            </span>
                                                        </td>
                                                    </tr>   

                                                @endif
                                                    
                                                        
                                            
                                        <?php 
                                            }
                                        }
                                        ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="dropzone">
                                    <form action="/subject/topic-upload-post" class="dropzone needsclick" id="real-dropzone" enctype="multipart/form-data">

                                        {{ csrf_field() }}
                                        
                                        <input type="hidden" name="topic_id" value="{{ $fetch_all_topic[$i]['id'] }}">

                                        <input type="hidden" name="subject_id" value="{{ $fetch_subject_details['id'] }}">

                                        <div class="fallback">
                                            <input name="file" type="file" multiple />
                                        </div>

                                        <div class="dz-message needsclick">
                                            Drop files here or click to upload.<br />
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>

<div class="modal fade" id="modal-topic-add" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Topic</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <form action="javascript:void(0)" id="topic_add_form" name="topic_add_form">
                    <input type="hidden" name="subject_id" id="subject_id" value="{{ $fetch_subject_details['id'] }}">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Subject</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Subject Name" name="subject_name" id="subject_name" value="{{ $fetch_subject_details['sub_full_name'] }}" disabled="">
                                </div>
                            </div>
                            <br/>
                            <br/>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Topic Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Topic Name" name="topic_name" id="topic_name">
                                </div>
                            </div>
                            <br/>
                            <br/>
                        </fieldset>

                        <div class="modal-footer"">
                            <button type="submit" class="btn btn-sm btn-success" id="topic_add_submit1">Submit</button>
                            <button type="button" class="btn btn-sm btn-white" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script type="text/javascript">
    $('.course_distribution_editor').ckeditor({ 
        height: 250,
        width: 700
    });
</script> --}}

@endsection