@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/question">Question</a></li>
            <li class="active">Add</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        

        <!-- end page-header -->
        <!-- begin profile-container -->
        <div class="profile-container">
            @if(Session::has('submit-status'))
                <p class="login-box-msg" style="color: red;">{{ Session::get('submit-status') }}</p>
            @endif
            <div class="row">
                <form exam="frmArea" method="POST" action="#" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject</label>
                        <div class="col-md-10 {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <select class="form-control" placeholder="Section subject" type="text" name="subject" id="subject">
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
                            <select class="form-control" placeholder="Section exam" type="text" name="exam" id="exam" subject_id=''>
                                <option value="">Select Exam</option>

                            </select>
                        </div>
                        @if ($errors->first('exam'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Area</label>
                        <div class="col-md-10 {{ $errors->has('area') ? 'has-error' : '' }}">
                            <select class="form-control" placeholder="Section exam" type="text" name="area" id="area">
                                <option value="">Select Area</option>
                            </select>
                        </div>
                        @if ($errors->first('area'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Section</label>
                        <div class="col-md-10 {{ $errors->has('section') ? 'has-error' : '' }}">
                            <select class="form-control" placeholder="Section exam" type="text" name="section" id="section">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        @if ($errors->first('section'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('section') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Question</label>
                        <div class="col-md-10 {{ $errors->has('question') ? 'has-error' : '' }}">
                            <textarea rows="12" cols="200" id="description" exam="description" placeholder="Write your message here..." class="editor form-control">
                                {{ old('question') }}
                            </textarea>

                        </div>
                        @if ($errors->first('question'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('question') }}</span>@endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            {{-- <button type="reset" class="btn btn-sm btn-default">Cancel</button> --}}
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
        
    </style>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#subject').on('change', function () {
                var subject_id = $(this).val();

                if(subject_id) {
                    $.ajax({
                        type: 'POST',
                        url: '/question/fetch-exam-subject-wise',
                        data: {
                            subject_id :subject_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $('#exam').attr('subject_id',subject_id);

                            $("#exam").find('option').not(':first').remove();
                            $("#section").find('option').not(':first').remove();
                            $("#area").find('option').not(':first').remove();

                            for(var i=0; i<response.tempArray.length;i++) {
                                $("#exam").append('<option value="'+response.tempArray[i].exam_id+'">'+response.tempArray[i].exam_name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });
                }
                else {
                    $("#exam").find('option').not(':first').remove();
                }
            });

            $('#exam').on('change', function () {
                var exam_id = $(this).val();
                var subject_id = $(this).attr('subject_id');

                if(exam_id){
                    $.ajax({
                        type: 'POST',
                        url: '/question/fetch-area-exam-wise',
                        data: {
                            exam_id : exam_id,
                            subject_id :subject_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $("#area").find('option').not(':first').remove();
                            for(var i=0; i<response.fetch_area.length;i++) {
                                $("#area").append('<option value="'+response.fetch_area[i].id+'">'+response.fetch_area[i].name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });

                }else{
                    $("#area").find('option').not(':first').remove();
                }
            });

            $('#area').on('change', function() {
                var area_id = $(this).val();

                if(area_id){
                    $.ajax({
                        type: 'POST',
                        url: '/question/fetch-section-area-wise',
                        data: {
                            area_id : area_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $("#section").find('option').not(':first').remove();
                            for(var i=0; i<response.fetch_section_details.length;i++) {
                                $("#section").append('<option value="'+response.fetch_section_details[i].id+'">'+response.fetch_section_details[i].name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });

                }else{
                    $("#section").find('option').not(':first').remove();
                }
            });
            
        });
    </script>
@endsection