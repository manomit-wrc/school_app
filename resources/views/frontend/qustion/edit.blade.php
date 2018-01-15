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
        <li class="active">Edit</li>
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
            <form exam="frmArea" method="POST" action="/question/add-question-edit/{{ $fetch_question_details['id'] }}" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="col-md-2 control-label">Subject</label>
                    <div class="col-md-10 {{ $errors->has('subject') ? 'has-error' : '' }}">
                        <select class="form-control" placeholder="Section subject" type="text" name="subject" id="subject">
                            <option value="0">Select Subject</option>
                            @foreach($fetch_all_subject as $key => $value)
                            <option value="{{ $key }}" @if($fetch_question_details['subject_id'] == $key) selected="selected" @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->first('subject'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject') }}</span>@endif
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Exam</label>
                    <div class="col-md-10 {{ $errors->has('exam') ? 'has-error' : '' }}">
                        <select class="form-control" placeholder="Section exam" type="text" name="exam[]" id="exam" subject_id='' multiple>
                            <option value="0">Select Exam</option>
                            @foreach($fetch_exam as $key => $value)
                            <option value="{{ $key }}" @if(in_array($key, $exam_ids)) selected="selected" @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->first('exam'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam') }}</span>@endif
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Area</label>
                    <div class="col-md-10 {{ $errors->has('area') ? 'has-error' : '' }}">
                        <select class="form-control" placeholder="Section exam" type="text" name="area" id="area">
                            <option value="0">Select Area</option>
                            @foreach($fetch_area as $key => $value)
                            <option value="{{ $key }}" @if($fetch_question_details['area_id']==$key) selected="selected" @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->first('area'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area') }}</span>@endif
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Section</label>
                    <div class="col-md-10 {{ $errors->has('section') ? 'has-error' : '' }}">
                        <select class="form-control" placeholder="Section exam" type="text" name="section" id="section">
                            <option value="0">Select Section</option>
                            @foreach($fetch_section as $key => $value)
                            <option value="{{ $key }}" @if($fetch_question_details['section_id']==$key) selected="selected" @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Level</label>
                    <div class="col-md-10 {{ $errors->has('level') ? 'has-error' : '' }}">
                        <select class="form-control" placeholder="Level exam" name="level" id="level">
                            <option value="0">Select Level</option>
                            <option value="1" @if($fetch_question_details['level'] == 1) selected="selected" @endif>Level 1</option>
                            <option value="2" @if($fetch_question_details['level'] == 2) selected="selected" @endif>Level 2</option>
                            <option value="3" @if($fetch_question_details['level'] == 3) selected="selected" @endif>Level 3</option>
                            <option value="4" @if($fetch_question_details['level'] == 4) selected="selected" @endif>Level 4</option>
                            <option value="5" @if($fetch_question_details['level'] == 5) selected="selected" @endif>Level 5</option>
                            <option value="6" @if($fetch_question_details['level'] == 6) selected="selected" @endif>Area Test</option>
                            <option value="7" @if($fetch_question_details['level'] == 7) selected="selected" @endif>Subject Test</option>
                            <option value="8" @if($fetch_question_details['level'] == 8) selected="selected" @endif>Full Length Test</option>
                        </select>
                    </div>
                    @if ($errors->first('level'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('level') }}</span>@endif
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Question</label>
                    <div class="col-md-10 {{ $errors->has('question') ? 'has-error' : '' }}">
                        <label class="radio-inline">
                            <input type="radio" name="question_type" value="text" id="question_type_text" @if($fetch_question_details['question_type'] == 'text') checked="checked" @endif>
                            Text
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="question_type" value="image" id="question_type_file" @if($fetch_question_details['question_type'] == 'image') checked="checked" @endif>
                            Image
                        </label>

                        <div id="text_div">
                            @if($fetch_question_details['question_type'] == 'text')
                            <textarea rows="12" cols="200" id="question" name="question" placeholder="Write your message here..." class="editor form-control">
                                {{ $fetch_question_details['question'] }}
                            </textarea>
                            @else
                            <textarea rows="12" cols="200" id="question" name="question" placeholder="Write your message here..." class="editor form-control">
                            </textarea>
                            @endif
                        </div>

                        <div id="image_div">
                            <input type="file" name="question_image" id="question_image" class="form-control">
                            @if($fetch_question_details['question_type'] == 'image')
                            <br>
                            <img src="{{ url('upload/question_file/resize/'.$fetch_question_details['question']) }}" style="width: 50px;height: 50px;">
                            <input type="hidden" name="exit_question_image" id="exit_question_image" value="{{ $fetch_question_details['question'] }}">
                            @endif
                        </div>
                    </div>
                    @if ($errors->first('question'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('question') }}</span>@endif
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Option Type</label>
                    <div class="col-md-10">
                        <label class="radio-inline">
                            <input type="radio" name="option_type" value="mcq" id="option_type_mcq" @if($fetch_question_details['option_type'] == 'mcq') checked="checked" @endif>
                            MCQ
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="option_type" value="numeric" id="option_type_numeric" @if($fetch_question_details['option_type'] == 'numeric') checked="checked" @endif>
                            Numeric
                        </label>
                    </div>
                    @if ($errors->first('option_type'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('option_type') }}</span>@endif
                </div>

                <div id="all_options">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Option A</label>
                        @if(!empty($option))
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_A" value="text" id="question_typeA_text" @if($option['optionA_type'] == 'text') checked="checked" @endif>
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_A" value="image" id="question_typeA_file" @if($option['optionA_type'] == 'image') checked="checked" @endif>
                                    Image
                                </label>
                                <div id="option_A_text_div">                               
                                    @if($option['optionA_type'] == 'text')
                                    <textarea class="form-control" cols="" rows="" name="optionA" id="optionA">{{ $option['optionA'] }}</textarea>
                                    @endif
                                </div>
                                <div id="option_A_image_div">
                                    <input type="file" name="optionA_file" id="optionA_file" class="form-control">
                                    @if($option['optionA_type'] == 'image')
                                    <br>
                                    <img src="{{ url('upload/answers_file/resize/'.$option['optionA']) }}" style="width: 50px;height: 50px;">
                                    <input type="hidden" name="exit_optionA_image" id="exit_optionA_image" value="{{ $option['optionA'] }}">
                                    @endif
                                </div>
                            </div>
                        @else
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
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Option B</label>
                        @if(!empty($option))
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_B" value="text" id="question_typeB_text" @if($option['optionB_type'] == 'text') checked="checked" @endif>
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_B" value="image" id="question_typeB_file" @if($option['optionB_type'] == 'image') checked="checked" @endif>
                                    Image
                                </label>
                                <div id="option_B_text_div">
                                    @if($option['optionB_type'] == 'text')
                                    <textarea class="form-control" cols="" rows="" name="optionB" id="optionB">{{ $option['optionB'] }}</textarea>
                                    @endif  
                                </div>
                                <div id="option_B_image_div">
                                    <input type="file" name="optionB_file" id="optionB_file" class="form-control">
                                    @if($option['optionB_type'] == 'image')
                                    <br>
                                    <img src="{{ url('upload/answers_file/resize/'.$option['optionB']) }}" style="width: 50px;height: 50px;">
                                    <input type="hidden" name="exit_optionB_image" id="exit_optionB_image" value="{{ $option['optionB'] }}">
                                    @endif
                                </div>
                            </div>
                        @else
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
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Option C</label>
                        @if(!empty($option))
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_C" value="text" id="question_typeC_text" @if($option['optionC_type'] == 'text') checked="checked" @endif>
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_C" value="image" id="question_typeC_file" @if($option['optionC_type'] == 'image') checked="checked" @endif>
                                    Image
                                </label>
                                <div id="option_C_text_div">
                                    @if($option['optionC_type'] == 'text')
                                    <textarea class="form-control" cols="" rows="" name="optionC" id="optionC">{{ $option['optionC'] }}</textarea>
                                    @endif
                                </div>
                                <div id="option_C_image_div">
                                    <input type="file" name="optionC_file" id="optionC_file" class="form-control">
                                    @if($option['optionC_type'] == 'image')
                                    <br>
                                    <img src="{{ url('upload/answers_file/resize/'.$option['optionC']) }}" style="width: 50px;height: 50px;">
                                    <input type="hidden" name="exit_optionC_image" id="exit_optionC_image" value="{{ $option['optionC'] }}">
                                    @endif
                                </div>
                            </div>
                        @else
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
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Option D</label>
                        @if(!empty($option))
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_D" value="text" id="question_typeD_text" @if($option['optionD_type'] == 'text') checked="checked" @endif>
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_D" value="image" id="question_typeD_file" @if($option['optionD_type'] == 'image') checked="checked" @endif>
                                    Image
                                </label>
                                <div id="option_D_text_div">
                                    @if($option['optionD_type'] == 'text')
                                        <textarea class="form-control" cols="" rows="" name="optionD" id="optionD">{{ $option['optionD'] }}</textarea>
                                    @endif
                                </div>
                                <div id="option_D_image_div">
                                    <input type="file" name="optionD_file" id="optionD_file" class="form-control">
                                    @if($option['optionD_type'] == 'image')
                                    <br>
                                    <img src="{{ url('upload/answers_file/resize/'.$option['optionD']) }}" style="width: 50px;height: 50px;">
                                    <input type="hidden" name="exit_optionD_image" id="exit_optionD_image" value="{{ $option['optionD'] }}">
                                    @endif
                                </div>
                            </div>
                        @else
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
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Option E</label>
                        @if(!empty($option))
                            <div class="col-md-10 {{ $errors->has('optionA') ? 'has-error' : '' }}">
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_E" value="text" id="question_typeE_text" @if($option['optionE_type'] == 'text') checked="checked" @endif>
                                    Text
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="option_type_E" value="image" id="question_typeE_file" @if($option['optionE_type'] == 'image') checked="checked" @endif>
                                    Image
                                </label>
                                <div id="option_E_text_div">
                                    @if($option['optionE_type'] == 'text')
                                        <textarea class="form-control" cols="" rows="" name="optionE" id="optionE">{{ $option['optionE'] }}</textarea>
                                    @endif
                                </div>
                                <div id="option_E_image_div">
                                    <input type="file" name="optionE_file" id="optionE_file" class="form-control">
                                    @if($option['optionE_type'] == 'image')
                                    <br>
                                    <img src="{{ url('upload/answers_file/resize/'.$option['optionE']) }}" style="width: 50px;height: 50px;">
                                    <input type="hidden" name="exit_optionE_image" id="exit_optionE_image" value="{{ $option['optionE'] }}">
                                    @endif
                                </div>
                            </div>
                        @else
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
                        @endif

                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Correct Answer</label>
                        @if(!empty($correct_answer))
                            <div class="col-md-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="1" @if(in_array(1, $correct_answer)) checked="checked" @endif>
                                    A
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="2" @if(in_array(2, $correct_answer)) checked="checked" @endif>
                                    B
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="3" @if(in_array(3, $correct_answer)) checked="checked" @endif>
                                    C
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="4" @if(in_array(4, $correct_answer)) checked="checked" @endif>
                                    D
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="answer[]" value="5" @if(in_array(5, $correct_answer)) checked="checked" @endif>
                                    E
                                </label>
                            </div>
                        @else
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
                        @endif
                        @if ($errors->first('answer'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('answer') }}</span>@endif
                    </div> 
                </div>

                <div id="numeric_correct_answer_div">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Correct Answer</label>
                        <div class="col-md-10">
                            <input type="text" name="numeric_correct_ans" class="form-control" value="{{ $fetch_question_details['numeric_answer'] }}">
                        </div>
                        @if ($errors->first('answer'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('answer') }}</span>@endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Explanation Details</label>
                    <div class="col-md-10">
                        <textarea cols="" rows="" class="form-control" name="explanation_details" id="explanation_details" style="height: 200px;">{{ $fetch_question_details['explanation_details'] }}</textarea>
                    </div>
                    @if ($errors->first('explanation_details'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('explanation_details') }}</span>@endif
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Explanation File</label>
                    <div class="col-md-10">
                        <input type="file" name="explanation_file" id="explanation_file" class="form-control" accept=".jpeg,.png,.jpg,.pdf" />
                        <span class="pull-left">Allowed file types .jpeg, .png, .jpg, .pdf and file should be within 6MB</span>
                        <br><br>
                        {{ $fetch_question_details['explanation_file'] }}
                        <input type="hidden" name="exit_explanation_image" id="exit_explanation_image" value="{{ $fetch_question_details['explanation_file'] }}">
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
    $(document).ready(function(){
        @if($fetch_question_details['option_type'] == 'mcq')
        $('#all_options').show();
        $('#numeric_correct_answer_div').hide();
        @elseif($fetch_question_details['option_type'] == 'numeric')
        $('#all_options').hide();
        $('#numeric_correct_answer_div').show();
        @else
        $('#all_options').hide();
        $('#numeric_correct_answer_div').hide();
        @endif

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
            CKEDITOR.replace('question');
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

            // $('#reset_form').on('click',function () {
            //     $('#image_div').hide();
            //     $('#text_div').hide();

            //     $('#option_A_text_div').hide();
            //     $('#option_A_image_div').hide();

            //     $('#option_B_text_div').hide();
            //     $('#option_B_image_div').hide();

            //     $('#option_C_text_div').hide();
            //     $('#option_C_image_div').hide();

            //     $('#option_D_text_div').hide();
            //     $('#option_D_image_div').hide();

            //     $('#option_E_text_div').hide();
            //     $('#option_E_image_div').hide();
            // });

            if($('#question_type_text').attr('checked')){
                CKEDITOR.replace('question');
                $('#text_div').show();
            }
            if($('#question_type_file').attr('checked')){
                $('#image_div').show();
            }

            if($('#question_typeA_text').attr('checked')){
                $('#option_A_text_div').show();
            }
            if($('#question_typeA_file').attr('checked')){
                $('#option_A_image_div').show();
            }

            if($('#question_typeB_text').attr('checked')){
                $('#option_B_text_div').show();
            }
            if($('#question_typeB_file').attr('checked')){
                $('#option_B_image_div').show();
            }

            if($('#question_typeC_text').attr('checked')){
                $('#option_C_text_div').show();
            }
            if($('#question_typeC_file').attr('checked')){
                $('#option_C_image_div').show();
            }

            if($('#question_typeD_text').attr('checked')){
                $('#option_D_text_div').show();
            }
            if($('#question_typeD_file').attr('checked')){
                $('#option_D_image_div').show();
            }

            if($('#question_typeE_text').attr('checked')){
                $('#option_E_text_div').show();
            }
            if($('#question_typeE_file').attr('checked')){
                $('#option_E_image_div').show();
            }

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
                            $('#exam').attr('subject_id', subject_id);
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
                } else {
                    $("#exam").find('option').not(':first').remove();
                }
            });

            $('#exam').on('change', function () {
                var exam_id = $(this).val();
                var subject_id = $(this).attr('subject_id');
                if (exam_id) {
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
                            for(var i = 0; i < response.fetch_area.length; i++) {
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
                            for(var i = 0; i < response.fetch_section_details.length; i++) {
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