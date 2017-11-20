@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
		<li><a href="/subject">Subject</a></li>
        <li><a href="/subject/topic-add/{{ $fetch_topic['subject_id'] }}">{{ $fetch_topic['topic_name'] }}</a></li>
	</ol>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">{{ $fetch_content_details['title'] }}</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <?php
                                    if(!empty($fetch_content_details['content_upload_details'])){
                                        foreach ($fetch_content_details['content_upload_details'] as $key => $value) {
                                            $path = $value['upload_file'];
                                            $file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                ?>
                                            @if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png')

                                                <tr>
                                                    <td>
                                                        <a href="{{ url('/upload/section_file/original/'.$value['upload_file']) }}" target="_blank">

                                                            {{ $value['upload_file'] }}
                                                        </a>

                                                        <span class="pull-right">
                                                            <a title="Delete" href="/subject/content-upload-file-delete/{{ $fetch_topic['subject_id'] }}/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                        </span>
                                                    </td>
                                                </tr>

                                            @else

                                                <tr>
                                                    <td>
                                                        <a href="{{ url('/upload/section_file/others/'.$value['upload_file']) }}" target="_blank">

                                                            {{ $value['upload_file'] }}
                                                        </a>

                                                        <span class="pull-right">
                                                            <a title="Delete" href="/subject/content-upload-file-delete/{{ $fetch_topic['subject_id'] }}/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                        </span>
                                                    </td>
                                                </tr>   

                                            @endif
                                                
                                                    
                                        
                                <?php 
                                        }
                                    }
                                ?>

                                @if(!empty($fetch_content_details['content_dropbox_details']))
                                    @foreach($fetch_content_details['content_dropbox_details'] as $key => $value)
                                        <tr>
                                            <td>
                                                <a href="{{ $value['link'] }}" target="_blank">

                                                    {{ $value['link'] }}
                                                </a>

                                                <span class="pull-right">
                                                    <a title="Delete" href="/subject/content-dropbox-file-delete/{{ $fetch_topic['subject_id'] }}/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if(!empty($fetch_content_details['content_embed_details']))
                                    @foreach($fetch_content_details['content_embed_details'] as $key => $value)
                                        <tr>
                                            <td>
                                                <a href="{{ $value['link'] }}" target="_blank">

                                                    {{ $value['link'] }}
                                                </a>

                                                <span class="pull-right">
                                                    <a title="Delete" href="/subject/content-embedVideo-file-delete/{{ $fetch_topic['subject_id'] }}/{{ $value['id'] }}" onclick="return confirm('Do you really want to delete the current record ?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>


@endsection