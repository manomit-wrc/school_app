@extends('dashboard_layout')
<!-- end #header -->

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
                <form name="frmStudyMat" method="POST" action="/study_mat/add-study-mat-submit" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject</label>
                        <div class="col-md-10 {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <select class="form-control" placeholder="Subject" type="text" name="subject" id="subject">
                                <option value="">Select Subject</option>
                                @foreach($fetch_all_subject as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('subject'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Area</label>
                        <div class="col-md-10 {{ $errors->has('area') ? 'has-error' : '' }}">
                            <select class="form-control" placeholder="Area" type="text" name="area" id="area">
                                <option value="">Select Area</option>
                            </select>
                        </div>
                        @if ($errors->first('area'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Section</label>
                        <div class="col-md-10 {{ $errors->has('section') ? 'has-error' : '' }}">
                            <select class="form-control" placeholder="Section" type="text" name="section" id="section">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        @if ($errors->first('section'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('section') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Video</label>
                        <div class="col-md-10">
                            <input type="file" name="video_files[]" id="video_files" class="form-control" onchange="handleVideos();" accept=".mp4,.mpeg,.avi,.wmv" multiple />
                        </div>
                        <ul id="video_sortable" class="ui-sortable"></ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Pdf</label>
                        <div class="col-md-10">
                            <input type="file" name="pdf_files[]" id="pdf_files" class="form-control" onchange="handlePdfs();" accept=".pdf" multiple />
                        </div>
                        <ul id="pdf_sortable" class="ui-sortable"></ul>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Add Document</label>
                        <div class="col-md-10">
                            <input type="file" name="doc_files[]" id="doc_files" class="form-control" onchange="handleDocs();" accept=".doc, .docx, .txt" multiple />
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
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
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

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">
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

        var inputElement = document.getElementById("video_files");
        inputElement.addEventListener("change", handleVideos, false);
        function handleVideos() {
            var fileList = this.files;
            for (var i = 0, numFiles = fileList.length; i < numFiles; i++) {
                $('#video_sortable').append('<li class="ui-state-default">'+fileList[i].name+'</li>');
            }
        }

        var inputElement = document.getElementById("pdf_files");
        inputElement.addEventListener("change", handlePdfs, false);
        function handlePdfs() {
            var fileList = this.files;
            for (var i = 0, numFiles = fileList.length; i < numFiles; i++) {
                $('#pdf_sortable').append('<li class="ui-state-default">'+fileList[i].name+'</li>');
            }
        }

        var inputElement = document.getElementById("doc_files");
        inputElement.addEventListener("change", handleDocs, false);
        function handleDocs() {
            var fileList = this.files;
            for (var i = 0, numFiles = fileList.length; i < numFiles; i++) {
                $('#doc_sortable').append('<li id="doc_'+i+'" class="ui-state-default">'+fileList[i].name+'</li>');
            }
            //$('#doc_sortable').append('<input type="text" id="doc_order" name="doc_order" />');
        }

        $(document).ready(function() {
            $( "#video_sortable" ).sortable();
            $( "#video_sortable" ).disableSelection();

            $( "#pdf_sortable" ).sortable();
            $( "#pdf_sortable" ).disableSelection();

            $( "#doc_sortable" ).sortable();
            $( "#doc_sortable" ).disableSelection();
        });
    </script>
@endsection