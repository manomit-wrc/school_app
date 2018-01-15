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
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Exam</label>
                        <div class="col-md-10 {{ $errors->has('exam') ? 'has-error' : '' }}">
                            <select class="form-control" name="exam[]" id="exam" multiple>
                                <option value="">Select Exam</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Area</label>
                        <div class="col-md-10">
                            <select class="form-control" name="area" id="area">
                                <option value="">Select Area</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Section</label>
                        <div class="col-md-10">
                            <select class="form-control" name="section" id="section">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Videos Section</label>
                        <div class="col-md-9" id="dynamic-div-video">
                            <div class="div-border">
                                <input type="text" name="video_name[]" id="video_name1" class="form-control video_name" placeholder="Video Name" /><br />
                                <textarea name="video_desc[]" id="video_desc1" class="form-control video_desc" placeholder="Video Description"></textarea><br />
                                <input type="file" name="video_files[]" id="video_file1" class="form-control video_file" accept=".mp4"  />
                                <span class="pull-left">Allowed file types .mp4 [User can upload multiple videos by adding more]</span><br /><br />
                                <input type="text" name="video_order[]" id="video_order1" class="video_order" placeholder="Video Order" />
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-sm add_div_video" title="Add more video section"><i class="fa fa-plus"></i></button>
                            <button class="btn btn-danger btn-sm remove_div_video" style="visibility: hidden;" title="remove video section"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Course Structure</label>
                        <div class="col-md-10">
                            <input type="file" name="pdf_files[]" id="pdf_files" class="form-control" onchange="handlePdfs();" accept=".pdf, .jpeg, .jpg, .png" multiple />
                            <span class="pull-left">Allowed file types .pdf, .jpeg, .jpg, .png [User can upload multiple images or pdfs at a time or separately]</span>
                        </div>
                        <ul id="pdf_sortable" class="ui-sortable"></ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Theory Section</label>
                        <div class="col-md-9" id="dynamic-div-theory">
                            <div class="div-border">
                                <input type="text" name="theory_name[]" id="theory_name1" class="form-control theory_name" placeholder="Theory Name" /><br />
                                <textarea name="theory_desc[]" id="theory_desc1" class="form-control theory_desc" placeholder="Theory Description"></textarea><br />
                                <input type="file" name="theory_files[]" id="theory_file1" class="form-control theory_file" accept=".pdf" />
                                <span class="pull-left">Allowed file types .pdf [User can upload multiple documents (pdf) by adding more]</span><br /><br />
                                <input type="text" name="theory_order[]" id="theory_order1" class="theory_order" placeholder="Theory Order" />
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-sm add_div_theory" title="Add more theory section"><i class="fa fa-plus"></i></button>
                            <button class="btn btn-danger btn-sm remove_div_theory" style="visibility: hidden;" title="remove theory section"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Description</label>
                        <div class="col-md-10">
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Duration (Hrs)</label>
                        <div class="col-md-10">
                            <input type="text" name="duration" id="duration" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Sample Questions</label>
                        <div class="col-md-9" id="dynamic-div">
                            <div class="div-border">
                                <textarea name="sample_ques[]" id="sample_ques1" class="form-control sample_ques" placeholder="Sample Question"></textarea><br />
                                <textarea name="sample_ans[]" id="sample_ans1" class="form-control sample_ans" placeholder="Sample Answer"></textarea><br />
                                <input type="text" name="ques_order[]" id="ques_order1" class="ques_order" placeholder="Question Order" />
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
                            <a href="/study_mat" class="btn btn-sm btn-default">Cancel</a>
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
        #pdf_sortable { width: 50%; float: left; margin-left: 18%; margin-top: 5px; padding: 0; }
        #pdf_sortable li { list-style: outside none none; padding: 5px 10px; cursor: move; }
        .div-border { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        input[type="file"] { height: auto; }
    </style>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        var formdata = new FormData();
        
        $(document).ready(function() {
            $("#exam").select2({
                placeholder: 'Select Exams',
            });

            $('#subject').on('change', function () {
                var subject_id = $(this).val();
                if (subject_id) {
                    $('#study_mat_submit').prop('disabled', false);
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
                    $('#study_mat_submit').prop('disabled', false);
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

            $('#section').on('change', function() {
                $('#study_mat_submit').prop('disabled', false);
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
        $(document).on("change", ".video_file", function(e) {
            formdata.append('video_files[]', this.files[0]);
        });

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
        $(document).on("change", ".theory_file", function(e) {
            formdata.append('doc_files[]', this.files[0]);
        });

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
                    formdata.append('description', CKEDITOR.instances['description'].getData());
                    formdata.append('duration', $("#duration").val());
                    formdata.append('_token', '{{csrf_token()}}');
                    /*$(".li-video").each(function(index) {
                        formdata.append('video_order[]', $(this).text());
                    });*/
                    $(".li-pdf").each(function(index) {
                        formdata.append('pdf_order[]', $(this).text());
                    });
                    /*$(".li-doc").each(function(index) {
                        formdata.append('doc_order[]', $(this).text());
                    });*/
                    $(".sample_ques").each(function(index) {
                        formdata.append('sample_questions[]', $(this).val());
                    });
                    $(".sample_ans").each(function(index) {
                        formdata.append('sample_answers[]', $(this).val());
                    });
                    $(".ques_order").each(function(index) {
                        formdata.append('ques_order[]', $(this).val());
                    });
                    $(".video_name").each(function(index) {
                        formdata.append('video_name[]', $(this).val());
                    });
                    $(".video_desc").each(function(index) {
                        formdata.append('video_desc[]', $(this).val());
                    });
                    $(".video_order").each(function(index) {
                        formdata.append('video_order[]', $(this).val());
                    });
                    $(".theory_name").each(function(index) {
                        formdata.append('theory_name[]', $(this).val());
                    });
                    $(".theory_desc").each(function(index) {
                        formdata.append('theory_desc[]', $(this).val());
                    });
                    $(".theory_order").each(function(index) {
                        formdata.append('theory_order[]', $(this).val());
                    });
                    $.ajax({
                        type: "POST",
                        url: '/study_mat/add-study-mat-submit',
                        processData: false,
                        contentType: false,
                        data: formdata,
                        success: function (data) {
                            if (data.code == 500) {
                                swal(data.message);
                            } else if (data == 1) {
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
            var iCntVdo = 1;
            var iCntThry = 1;

            $('.add_div').on('click', function () {
                iCnt = iCnt + 1;
                $('#dynamic-div').append('<div id="div-'+iCnt+'" class="div-border"><textarea name="sample_ques[]" id="sample_ques'+iCnt+'" class="form-control sample_ques" placeholder="Sample Question"></textarea><br /><textarea name="sample_ans[]" id="sample_ans'+iCnt+'" class="form-control sample_ans" placeholder="Sample Answer"></textarea><br /><input type="text" name="ques_order[]" id="ques_order'+iCnt+'" class="ques_order" placeholder="Question Order" /></div>');
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

            $('.add_div_video').on('click', function () {
                iCntVdo = iCntVdo + 1;
                $('#dynamic-div-video').append('<div id="video-'+iCntVdo+'" class="div-border"><input type="text" name="video_name[]" id="video_name'+iCntVdo+'" class="form-control video_name" placeholder="Video Name" /><br /><textarea name="video_desc[]" id="video_desc'+iCntVdo+'" class="form-control video_desc" placeholder="Video Description"></textarea><br /><input type="file" name="video_files[]" id="video_file'+iCntVdo+'" class="form-control video_file" accept=".mp4"  /><span class="pull-left">Allowed file types .mp4 [User can upload multiple videos by adding more]</span><br /><br /><input type="text" name="video_order[]" id="video_order'+iCntVdo+'" class="video_order" placeholder="Video Order" /></div>');
                $('.remove_div_video').css('visibility', 'visible');
                return false;
            });
            
            $('.remove_div_video').on('click', function () {
                if (iCntVdo != 1) {
                    $('#video-'+iCntVdo).remove();
                    iCntVdo = iCntVdo - 1;
                }
                if (iCntVdo == 1) $('.remove_div_video').css('visibility', 'hidden');
                return false;
            });

            $('.add_div_theory').on('click', function () {
                iCntThry = iCntThry + 1;
                $('#dynamic-div-theory').append('<div id="theory-'+iCntThry+'" class="div-border"><input type="text" name="theory_name[]" id="theory_name'+iCntThry+'" class="form-control theory_name" placeholder="Theory Name" /><br /><textarea name="theory_desc[]" id="theory_desc'+iCntThry+'" class="form-control theory_desc" placeholder="Theory Description"></textarea><br /><input type="file" name="theory_files[]" id="theory_file'+iCntThry+'" class="form-control theory_file" accept=".pdf" onchange="handleDocs();" /><span class="pull-left">Allowed file types .pdf [User can upload multiple documents (pdf) by adding more]</span><br /><br /><input type="text" name="theory_order[]" id="theory_order'+iCntThry+'" class="theory_order" placeholder="Theory Order" /></div>');
                $('.remove_div_theory').css('visibility', 'visible');
                return false;
            });
            
            $('.remove_div_theory').on('click', function () {
                if (iCntThry != 1) {
                    $('#theory-'+iCntThry).remove();
                    iCntThry = iCntThry - 1;
                }
                if (iCntThry == 1) $('.remove_div_theory').css('visibility', 'hidden');
                return false;
            });
        });
    </script>
@endsection