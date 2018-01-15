@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/question">Exam Question</a></li>
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
                <form exam="frmQuestion" method="POST" action="/exam_question/add-submit" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="col-md-2 control-label">Exam</label>
                        <div class="col-md-10 {{ $errors->has('exam') ? 'has-error' : '' }}">
                            <select class="form-control" name="exam" id="exam">
                                <option value="0">Select Exam</option>
                                @foreach($fetch_all_exam as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('exam'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam') }}</span>@endif
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
                            <input type="file" name="explanation_file" id="explanation_file" class="form-control" accept=".jpeg,.png,.jpg" />
                            <span class="pull-left">Allowed file types .jpeg, .png, .jpg and file should be within 6MB</span>
                        </div>
                        @if ($errors->first('explanation_file'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('explanation_file') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            <a href="/exam_question" class="btn btn-sm btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end profile-container -->
    </div>
    <!-- end #content -->
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
        });
    </script>
@endsection