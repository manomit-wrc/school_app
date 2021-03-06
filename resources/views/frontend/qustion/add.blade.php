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
                <form exam="frmArea" method="POST" action="/question/add-question-submit" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject</label>
                        <div class="col-md-10 {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <select class="form-control" name="subject" id="subject">
                                <option value="0">Select Subject</option>
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
                                <option value="0">Select Exam</option>
                                {{--@foreach($fetch_all_exam as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach--}}
                            </select>
                        </div>
                        @if ($errors->first('exam'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Area</label>
                        <div class="col-md-10 {{ $errors->has('area') ? 'has-error' : '' }}">
                            <select class="form-control" name="area" id="area">
                                <option value="0">Select Area</option>
                            </select>
                        </div>
                        @if ($errors->first('area'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Section</label>
                        <div class="col-md-10 {{ $errors->has('section') ? 'has-error' : '' }}">
                            <select class="form-control" name="section" id="section">
                                <option value="0">Select Section</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Level</label>
                        <div class="col-md-10 {{ $errors->has('level') ? 'has-error' : '' }}">
                            <select class="form-control" name="level" id="level">
                                <option value="0">Select Level</option>
                                <option value="1">Level 1</option>
                                <option value="2">Level 2</option>
                                <option value="3">Level 3</option>
                                <option value="4">Level 4</option>
                                <option value="5">Level 5</option>
                                <option value="6">Area Test</option>
                                <option value="7">Subject Test</option>
                                <option value="8">Full Length Test</option>
                            </select>
                        </div>
                        @if ($errors->first('level'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('level') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Question</label>
                        <div class="col-md-10">
                            <label class="radio-inline">
                                <input type="radio" name="question_type" value="text" id="question_type_text">
                                Text
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="question_type" value="image" id="question_type_file">
                                Image
                            </label>

                            <div id="text_div">
                                <textarea rows="12" cols="200" id="question" name="question" placeholder="Write your message here..." class="editor form-control">
                                {{ old('question') }}
                                </textarea>
                            </div>

                            <div id="image_div">
                                <input type="file" name="question_image" id="question_image" class="form-control">
                            </div>
                        </div>

                        @if ($errors->first('question_type'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('question_type') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Option Type</label>
                        <div class="col-md-10">
                            <label class="radio-inline">
                                <input type="radio" name="option_type" value="mcq" id="option_type_mcq">
                                MCQ
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="option_type" value="numeric" id="option_type_numeric">
                                Numeric
                            </label>
                            
                        </div>

                        @if ($errors->first('option_type'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('option_type') }}</span>@endif
                    </div>

                    <div id="all_options">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Option A</label>
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_A" value="text" id="question_typeA_text">
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_A" value="image" id="question_typeA_file">
                                    Image
                                </label>

                                <div id="option_A_text_div">
                                    <textarea class="form-control" cols="" rows="" name="optionA" id="optionA"></textarea>
                                </div>

                                <div id="option_A_image_div">
                                    <input type="file" name="optionA_file" id="optionA_file" class="form-control">
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Option B</label>
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_B" value="text" id="question_typeB_text">
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_B" value="image" id="question_typeB_file">
                                    Image
                                </label>

                                <div id="option_B_text_div">
                                    <textarea class="form-control" cols="" rows="" name="optionB" id="optionB"></textarea>
                                </div>

                                <div id="option_B_image_div">
                                    <input type="file" name="optionB_file" id="optionB_file" class="form-control">
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Option C</label>
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_C" value="text" id="question_typeC_text">
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_C" value="image" id="question_typeC_file">
                                    Image
                                </label>

                                <div id="option_C_text_div">
                                    <textarea class="form-control" cols="" rows="" name="optionC" id="optionC"></textarea>
                                </div>

                                <div id="option_C_image_div">
                                    <input type="file" name="optionC_file" id="optionC_file" class="form-control">
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Option D</label>
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_D" value="text" id="question_typeD_text">
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_D" value="image" id="question_typeD_file">
                                    Image
                                </label>

                                <div id="option_D_text_div">
                                    <textarea class="form-control" cols="" rows="" name="optionD" id="optionD"></textarea>
                                </div>

                                <div id="option_D_image_div">
                                    <input type="file" name="optionD_file" id="optionD_file" class="form-control">
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Option E</label>
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_E" value="text" id="question_typeE_text">
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_E" value="image" id="question_typeE_file">
                                    Image
                                </label>

                                <div id="option_E_text_div">
                                    <textarea class="form-control" cols="" rows="" name="optionE" id="optionE"></textarea>
                                </div>

                                <div id="option_E_image_div">
                                    <input type="file" name="optionE_file" id="optionE_file" class="form-control">
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Correct Answer</label>
                            <div class="col-md-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="1">
                                    A
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="2">
                                    B
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="3">
                                    C
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="4">
                                    D
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="5">
                                    E
                                </label>
                            </div>
                            @if ($errors->first('answer'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('answer') }}</span>@endif
                        </div>                        
                    </div>

                    <div id="numeric_correct_answer_div">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Correct Answer</label>
                            <div class="col-md-10">
                                <input type="text" name="numeric_correct_ans" class="form-control">
                            </div>
                            @if ($errors->first('answer'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('answer') }}</span>@endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Explanation Details</label>
                        <div class="col-md-10">
                            <textarea cols="" rows="" class="form-control" name="explanation_details" id="explanation_details" style="height: 200px;"></textarea>
                        </div>
                        @if ($errors->first('explanation_details'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('explanation_details') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Explanation File</label>
                        <div class="col-md-10">
                            <input type="file" name="explanation_file" id="explanation_file" class="form-control" accept=".jpeg,.png,.jpg,.pdf" />
                            <span class="pull-left">Allowed file types .jpeg, .png, .jpg, .pdf and file should be within 6MB</span>
                        </div>
                        @if ($errors->first('explanation_file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('explanation_file') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            <a href="/question" class="btn btn-sm btn-default">Cancel</a>
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
        $(document).ready(function() {
            $('#all_options').hide();
            $('#numeric_correct_answer_div').hide();

            $('#option_type_mcq').on('click', function () {
                $('#all_options').show();
                $('#numeric_correct_answer_div').hide();
            });
            $('#option_type_numeric').on('click', function (){
                $('#all_options').hide();
                $('#numeric_correct_answer_div').show();
            });

            $("#exam").select2({
                placeholder: 'Select Exams',
            });

            $('#image_div').hide();
            $('#text_div').hide();

            $('#question_type_text').on('click', function () {
                $('#text_div').show();
                CKEDITOR.replace( 'question', {
                    height: 300,
                    // Configure your file manager integration. This example uses CKFinder 3 for PHP.
                    filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
                    filebrowserImageBrowseUrl: '/ckfinder/ckfinder.html?type=Images',
                    filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                    filebrowserImageUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
                });
                $('#image_div').hide();
            });

            $('#question_type_file').on('click', function () {
                $('#text_div').hide();
                $('#image_div').show();
            });

            $('#option_A_text_div').hide();
            $('#option_A_image_div').hide();
            $('#question_typeA_text').on('click', function() {
                $('#option_A_text_div').show();
                $('#option_A_image_div').hide();
            });
            $('#question_typeA_file').on('click', function () {
                $('#option_A_text_div').hide();
                $('#option_A_image_div').show();
            });

            $('#option_B_text_div').hide();
            $('#option_B_image_div').hide();
            $('#question_typeB_text').on('click', function() {
                $('#option_B_text_div').show();
                $('#option_B_image_div').hide();
            });
            $('#question_typeB_file').on('click', function () {
                $('#option_B_text_div').hide();
                $('#option_B_image_div').show();
            });

            $('#option_C_text_div').hide();
            $('#option_C_image_div').hide();
            $('#question_typeC_text').on('click', function() {
                $('#option_C_text_div').show();
                $('#option_C_image_div').hide();
            });
            $('#question_typeC_file').on('click', function () {
                $('#option_C_text_div').hide();
                $('#option_C_image_div').show();
            });

            $('#option_D_text_div').hide();
            $('#option_D_image_div').hide();
            $('#question_typeD_text').on('click', function() {
                $('#option_D_text_div').show();
                $('#option_D_image_div').hide();
            });
            $('#question_typeD_file').on('click', function () {
                $('#option_D_text_div').hide();
                $('#option_D_image_div').show();
            });

            $('#option_E_text_div').hide();
            $('#option_E_image_div').hide();
            $('#question_typeE_text').on('click', function() {
                $('#option_E_text_div').show();
                $('#option_E_image_div').hide();
            });
            $('#question_typeE_file').on('click', function () {
                $('#option_E_text_div').hide();
                $('#option_E_image_div').show();
            });

            $('#reset_form').on('click',function () {
                $('#image_div').hide();
                $('#text_div').hide();

                $('#option_A_text_div').hide();
                $('#option_A_image_div').hide();

                $('#option_B_text_div').hide();
                $('#option_B_image_div').hide();

                $('#option_C_text_div').hide();
                $('#option_C_image_div').hide();

                $('#option_D_text_div').hide();
                $('#option_D_image_div').hide();

                $('#option_E_text_div').hide();
                $('#option_E_image_div').hide();

                $('#all_options').hide();
                $('#numeric_correct_answer_div').hide();
            });

            $('#subject').on('change', function () {
                var subject_id = $(this).val();
                if (subject_id) {
                    $.ajax({
                        type: 'POST',
                        url: '/question/fetch-exam-subject-wise',
                        data: {
                            subject_id : subject_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $('#exam').attr('subject_id', subject_id);
                            $("#exam").find('option').not(':first').remove();
                            $("#section").find('option').not(':first').remove();
                            $("#area").find('option').not(':first').remove();
                            for (var i = 0; i < response.tempArray.length; i++) {
                                $("#exam").append('<option value="'+response.tempArray[i].exam_id+'">'+response.tempArray[i].exam_name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });
                } else {
                    $("#exam").find('option').not(':first').remove();
                }
            });

            $('#exam').on('change', function () {
                var exam_id = $(this).val();
                var subject_id = $(this).attr('subject_id');
                if(exam_id) {
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
                            for (var i = 0; i < response.fetch_area.length; i++) {
                                $("#area").append('<option value="'+response.fetch_area[i].id+'">'+response.fetch_area[i].name+'</option>');
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
                        url: '/question/fetch-section-area-wise',
                        data: {
                            area_id : area_id,
                            _token : "{{ csrf_token() }}"
                        },
                        success:function(response) {
                            $("#section").find('option').not(':first').remove();
                            for (var i = 0; i < response.fetch_section_details.length; i++) {
                                $("#section").append('<option value="'+response.fetch_section_details[i].id+'">'+response.fetch_section_details[i].name+'</option>');
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
    </script>
@endsection