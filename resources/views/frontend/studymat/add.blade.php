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
            <li class="active">Add</li>
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
                <form name="frmStudyMat" id="frmStudyMat" method="POST" action="/study_mat/add-study-mat-submit" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject</label>
                        <div class="col-md-10">
                            <select class="form-control" name="subject" id="subject">
                                <option value="">Select Subject</option>
                                @foreach($fetch_all_subject as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('subject'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Exam</label>
                        <div class="col-md-10 {{ $errors->has('exam') ? 'has-error' : '' }}">
                            <select class="form-control" name="exam[]" id="exam" multiple>
                                <option value="">Select Exam</option>
                            </select>
                        </div>
                        @if ($errors->first('exam'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Area</label>
                        <div class="col-md-10">
                            <select class="form-control" name="area" id="area">
                                <option value="">Select Area</option>
                            </select>
                        </div>
                        @if ($errors->first('area'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Section</label>
                        <div class="col-md-10">
                            <select class="form-control" name="section" id="section">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        @if ($errors->first('section'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('section') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Video</label>
                        <div class="col-md-10">
                            <input type="file" name="video_files[]" id="video_files" class="form-control" onchange="handleVideos();" accept=".mp4,.mpeg,.avi,.wmv" multiple />
                            <span class="pull-left">Allowed file types .mp4, .mpeg, .avi, .wmv</span>
                        </div>
                        <ul id="video_sortable" class="ui-sortable"></ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Pdf or Image</label>
                        <div class="col-md-10">
                            <input type="file" name="pdf_files[]" id="pdf_files" class="form-control" onchange="handlePdfs();" accept=".pdf, .jpeg, .jpg, .png" multiple />
                            <span class="pull-left">Allowed file types .pdf, .jpeg, .jpg, .png</span>
                        </div>
                        <ul id="pdf_sortable" class="ui-sortable"></ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Document</label>
                        <div class="col-md-10">
                            <input type="file" name="doc_files[]" id="doc_files" class="form-control" onchange="handleDocs();" accept=".doc, .docx, .txt" multiple />
                            <span class="pull-left">Allowed file types .doc, .docx, .txt</span>
                        </div>
                        <ul id="doc_sortable" class="ui-sortable"></ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Description</label>
                        <div class="col-md-10">
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Duration (Hrs)</label>
                        <div class="col-md-10">
                            <input type="text" name="duration" id="duration" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Sample Questions</label>
                        <div class="col-md-9" id="dynamic-div">
                            <div class="div-border">
                                <textarea name="sample_ques[]" id="sample_ques1" class="form-control sample_ques" placeholder="Sample Question" /></textarea><br />
                                <textarea name="sample_ans[]" id="sample_ans1" class="form-control sample_ans" placeholder="Sample Answer" /></textarea>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-sm add_div" title="Add more sample questions"><i class="fa fa-plus"></i></button>
                            <button class="btn btn-danger btn-sm remove_div" style="visibility: hidden;" title="remove sample question"><i class="fa fa-minus"></i></button>
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
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #49b6d6!important;
            color: #fff!important;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff!important;
            cursor: pointer;
            display: inline-block;
            font-weight: bold;
            margin-right: 2px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #333!important;
        }
        #video_sortable, #pdf_sortable, #doc_sortable { width: 50%; float: left; margin-left: 18%; margin-top: 5px; padding: 0; }
        #video_sortable li, #pdf_sortable li, #doc_sortable li { list-style: outside none none; padding: 5px 10px; cursor: move; }
        .div-border { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
    </style>

    <script type="text/javascript">
        var formdata = new FormData();
        $(document).ready(function() {
            $("#exam").select2({
                placeholder: 'Select Exams',
            });

            $('#subject').on('change', function () {
                var subject_id = $(this).val();
                if (subject_id) {
                    $.ajax({
                        type: 'POST',
                        url: '/study_mat/fetch-subject-wise-exam',
                        data: {
                            subject_id : subject_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            console.log(response);
                            $("#exam").find('option').not(':first').remove();
                            for (var i = 0; i < response.tempArray.length; i++) {
                                $("#exam").append('<option value="'+response.tempArray[i].exam_id+'">'+response.tempArray[i].exam_name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: '/study_mat/fetch-subject-wise-area',
                        data: {
                            subject_id : subject_id,
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
                    $("#exam").find('option').not(':first').remove();
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
                if(!stat) {
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
                if(!stat) {
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
                if(!stat) {
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
                    exam:{
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
                        required:"<font color='red'>Please select subject</font>"
                    },
                    exam:{
                        required:"<font color='red'>Please select exam</font>"
                    },
                    area:{
                        required:"<font color='red'>Please select area</font>"
                    },
                    section:{
                        required:"<font color='red'>Please select section</font>"
                    }
                }
            });

            var video_order = [];
            var pdf_order = [];
            var doc_order = [];
            $('#study_mat_submit').on('click', function (e) {
                var valid = $('#frmStudyMat').valid();
                if (valid) {
                    $('#study_mat_submit').prop('disabled', true);
                    formdata.append('subject', $("#subject").val());
                    formdata.append('exam[]', $("#exam").val());
                    formdata.append('area', $("#area").val());
                    formdata.append('section', $("#section").val());
                    formdata.append('description', $("#description").val());
                    formdata.append('duration', $("#duration").val());
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
                    $(".sample_ques").each(function(index) {
                        formdata.append('sample_questions[]', $(this).val());
                    });
                    $(".sample_ans").each(function(index) {
                        formdata.append('sample_answers[]', $(this).val());
                    });
                    $.ajax({
                        type: "POST",
                        url: '/study_mat/add-study-mat-submit',
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
                e.preventDefault();
            });

            var iCnt = 1;

            $('.add_div').on('click', function () {
                iCnt = iCnt + 1;
                $('#dynamic-div').append('<div id="div-'+iCnt+'" class="div-border"><textarea name="sample_ques[]" id="sample_ques'+iCnt+'" class="form-control sample_ques" placeholder="Sample Question" /></textarea><br /><textarea name="sample_ans[]" id="sample_ans'+iCnt+'" class="form-control sample_ans" placeholder="Sample Answer" /></textarea></div>');
                $('.remove_div').css('visibility', 'visible');
                return false;
            });

            $('.remove_div').on('click', function () {
                if (iCnt != 1) {
                    $('#div-'+iCnt).remove();
                    iCnt = iCnt - 1;
                }
                if (iCnt == 1) $('.remove_div').css('visibility', 'hidden');
                return false;
            });
        });
    </script>
@endsection