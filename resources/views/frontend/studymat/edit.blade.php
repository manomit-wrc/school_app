@extends('dashboard_layout')
<!-- end #header -->

<style type="text/css">
    .error { text-align: left !important; }
</style>

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/stydy_mat">Study Material</a></li>
            <li class="active">Edit</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->

        <!-- end page-header -->
        <!-- begin profile-container -->
        <div class="profile-container">
            @if(Session::has('submit-status'))
                <p class="login-box-msg" style="color: green;">{{ Session::get('submit-status') }}</p>
            @endif

            @if(Session::has('error-status'))
                <p class="login-box-msg" style="color: red;">{{ Session::get('error-status') }}</p>
            @endif

            <div class="row">
                <form name="frmStudyMat" id="frmStudyMat" method="POST" action="/study_mat/study-mat-update" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="study_id" value="{{$fetch_study_mat['id']}}" />
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject</label>
                        <div class="col-md-10">
                            <select class="form-control" placeholder="Subject" type="text" name="subject" id="subject">
                                <option value="">Select Subject</option>
                                @foreach($fetch_all_subject as $key => $value)
                                    <option value="{{ $key }}" @if($fetch_study_mat['subject_id'] == $key) selected="selected"@endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('subject'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Area</label>
                        <div class="col-md-10">
                            <select class="form-control" placeholder="Area" type="text" name="area" id="area">
                                <option value="">Select Area</option>
                                @foreach($fetch_all_area as $key => $value)
                                    <option value="{{ $key }}" @if($fetch_study_mat['area_id'] == $key) selected="selected"@endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('area'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Section</label>
                        <div class="col-md-10">
                            <select class="form-control" placeholder="Section" type="text" name="section" id="section">
                                <option value="">Select Section</option>
                                @foreach($fetch_all_section as $key => $value)
                                    <option value="{{ $key }}" @if($fetch_study_mat['section_id'] == $key) selected="selected"@endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('section'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('section') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Video</label>
                        <div class="col-md-10">
                            <input type="file" name="video_files[]" id="video_files" class="form-control" onchange="handleVideos();" accept=".mp4,.mpeg,.avi,.wmv" multiple />
                        </div>
                        <ul id="video_sortable" class="ui-sortable">
                            @if(count($fetch_study_videos) > 0)
                                @foreach($fetch_study_videos as $study_video)
                                    <li class="ui-state-default li-video" id="video_{{$study_video['video_order']}}">{{$study_video['video']}}</li><a id="vlink_{{$study_video['video_order']}}" href="javascript:void(0);" onclick="del_video({{$study_video['video_order']}})"><i class="fa fa-trash"></i></a>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Pdf</label>
                        <div class="col-md-10">
                            <input type="file" name="pdf_files[]" id="pdf_files" class="form-control" onchange="handlePdfs();" accept=".pdf" multiple />
                        </div>
                        <ul id="pdf_sortable" class="ui-sortable">
                            @if(count($fetch_study_pdfs) > 0)
                                @foreach($fetch_study_pdfs as $study_pdf)
                                    <li class="ui-state-default li-pdf" id="pdf_{{$study_pdf['pdf_order']}}">{{$study_pdf['pdf']}}</li><a id="plink_{{$study_pdf['pdf_order']}}" href="javascript:void(0);" onclick="del_pdf({{$study_pdf['pdf_order']}})"><i class="fa fa-trash"></i></a>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Document</label>
                        <div class="col-md-10">
                            <input type="file" name="doc_files[]" id="doc_files" class="form-control" onchange="handleDocs();" accept=".doc, .docx, .txt" multiple />
                        </div>
                        <ul id="doc_sortable" class="ui-sortable">
                            @if(count($fetch_study_documents) > 0)
                                @foreach($fetch_study_documents as $study_doc)
                                    <li class="ui-state-default li-doc" id="doc_{{$study_doc['doc_order']}}">{{$study_doc['doc']}}</li><a id="dlink_{{$study_doc['doc_order']}}" href="javascript:void(0);" onclick="del_doc({{$study_doc['doc_order']}})"><i class="fa fa-trash"></i></a>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Description</label>
                        <div class="col-md-10">
                            <textarea name="description" id="description" class="form-control">{{$fetch_study_mat['description']}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" id="study_mat_submit" class="btn btn-sm btn-primary">Submit</button>
                            <button type="reset" class="btn btn-sm btn-default">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->

    <style type="text/css">
        #video_sortable, #pdf_sortable, #doc_sortable { width: 50%; float: left; margin-left: 18%; margin-top: 5px; padding: 0; }
        #video_sortable li, #pdf_sortable li, #doc_sortable li { list-style: outside none none; padding: 5px 10px; cursor: move; }
    </style>

    <script type="text/javascript">
        var formdata = new FormData();
        $(document).ready(function() {
            $('#subject').on('change', function () {
                var subject_id = $(this).val();
                if (subject_id) {
                    $.ajax({
                        type: 'POST',
                        url: '/study_mat/fetch-subject-wise-area',
                        data: {
                            subject_id :subject_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $("#area").find('option').not(':first').remove();
                            $("#section").find('option').not(':first').remove();

                            for(var i = 0; i < response.tempArray.length; i++) {
                                $("#area").append('<option value="'+response.tempArray[i].area_id+'">'+response.tempArray[i].area_name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });
                } else {
                    $("#area").find('option').not(':first').remove();
                }
            });

            $('#area').on('change', function() {
                var area_id = $(this).val();
                if (area_id) {
                    $.ajax({
                        type: 'POST',
                        url: '/study_mat/fetch-area-wise-section',
                        data: {
                            area_id : area_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $("#section").find('option').not(':first').remove();

                            for(var i = 0; i < response.tempArray.length; i++) {
                                $("#section").append('<option value="'+response.tempArray[i].id+'">'+response.tempArray[i].name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });
                } else {
                    $("#section").find('option').not(':first').remove();
                }
            });
        });

        function findPropertyWithValue(obj, val) {
            for (var i in obj) {
                if (obj.hasOwnProperty(i) && obj[i].name === val) {
                    return 1;
                }
            }
            return 0;
        }

        var video_files = [];
        var inputElement = document.getElementById("video_files");
        inputElement.addEventListener("change", handleVideos, false);
        function handleVideos() {
            var names = $.map(this.files, function(val) {
                var stat = findPropertyWithValue(video_files, val.name);
                if (!stat) {
                    formdata.append('video_files[]', val);
                    $('#video_sortable').append('<li class="ui-state-default li-video">'+val.name+'</li>');
                }
            });
        }

        var pdf_files = [];
        var inputElement = document.getElementById("pdf_files");
        inputElement.addEventListener("change", handlePdfs, false);
        function handlePdfs() {
            var names = $.map(this.files, function(val) {
                var stat = findPropertyWithValue(pdf_files, val.name);
                if (!stat) {
                    formdata.append('pdf_files[]', val);
                    $('#pdf_sortable').append('<li class="ui-state-default li-pdf">'+val.name+'</li>');
                }
            });
        }

        var doc_files = [];
        var inputElement = document.getElementById("doc_files");
        inputElement.addEventListener("change", handleDocs, false);
        function handleDocs() {
            var names = $.map(this.files, function(val) {
                var stat = findPropertyWithValue(doc_files, val.name);
                if (!stat) {
                    formdata.append('doc_files[]', val);
                    $('#doc_sortable').append('<li class="ui-state-default li-doc">'+val.name+'</li>');
                }
            });
        }

        $(document).ready(function() {
            $( "#video_sortable" ).sortable();
            $( "#video_sortable" ).disableSelection();

            $( "#pdf_sortable" ).sortable();
            $( "#pdf_sortable" ).disableSelection();

            $( "#doc_sortable" ).sortable();
            $( "#doc_sortable" ).disableSelection();


            $('#frmStudyMat').validate({
                rules:{
                    subject:{
                        required:true
                    },
                    area:{
                        required:true
                    },
                    section:{
                        required:true
                    }
                },
                messages:{
                    subject:{
                        required:"<font color='red'>Please select subject.</font>"
                    },
                    area:{
                        required:"<font color='red'>Please select area.</font>"
                    },
                    section:{
                        required:"<font color='red'>Please select section.</font>"
                    }
                }
            });

            $('#study_mat_submit').on('click',function () {
                var valid = $('#frmStudyMat').valid();
                if (valid) {
                    $('#study_mat_submit').prop('disabled', true);

                    formdata.append('subject', $("#subject").val());
                    formdata.append('area', $("#area").val());
                    formdata.append('section', $("#section").val());
                    formdata.append('_token', '{{csrf_token()}}');
                    $(".li-video").each(function(index) {
                        formdata.append('video_order[]', $(this).text());
                    });
                    $(".li-pdf").each(function(index) {
                        formdata.append('pdf_order[]', $(this).text());
                    });
                    $(".li-doc").each(function(index) {
                        formdata.append('doc_order[]', $(this).text());
                    });
                    $.ajax({
                        type: "POST",
                        url: '/study_mat/study-mat-update',
                        processData: false,
                        contentType: false,
                        data: formdata,
                        success: function (data) {
                            if (data == 1) {
                                window.location.href = '/study_mat';
                            } else if (data == 0) {
                                window.location.href = '/study_mat/add';
                            }
                        }
                    });
                }
            });
        });

        function del_video(video_id) {
            $('#video_'+video_id).remove();
            $('#vlink_'+video_id).remove();
        }

        function del_pdf(pdf_id) {
            $('#pdf_'+pdf_id).remove();
            $('#plink_'+pdf_id).remove();
        }

        function del_doc(doc_id) {
            $('#doc_'+doc_id).remove();
            $('#dlink_'+doc_id).remove();
        }
    </script>
@endsection