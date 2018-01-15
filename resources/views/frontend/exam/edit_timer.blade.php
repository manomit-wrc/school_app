@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="javascript:void(0);">Exam Timer</a></li>
    </ol>
    <br />
    <!-- end breadcrumb -->

	<!-- begin #content -->
    <div id="content" class="content">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Exam Timer Settings</h4>
            </div>
            <div class="panel-body">
                <!-- begin profile-container -->
                <div class="profile-container">
                    @if(Session::has('submit-status'))
                        <p class="login-box-msg" style="color: red;">{{ Session::get('submit-status') }}</p>
                    @endif
                    <div class="row">
                        <form name="frmExamTimer" method="POST" action="/update_exam_timer" class="form-horizontal">
                            {{ csrf_field() }}
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Section Test Time</label>
                                <div class="col-md-10 {{ $errors->has('section_test') ? 'has-error' : '' }}">
                                    <select name="section_test" id="section_test" class="form-control">
                                        <option value="10" @if($exam_timer) @if($exam_timer[0]['section_test'] == 10) selected="selected" @endif @endif>10</option>
                                        <option value="15" @if($exam_timer) @if($exam_timer[0]['section_test'] == 15) selected="selected" @endif @endif>15</option>
                                        <option value="20" @if($exam_timer) @if($exam_timer[0]['section_test'] == 20) selected="selected" @endif @endif>20</option>
                                        <option value="25" @if($exam_timer) @if($exam_timer[0]['section_test'] == 25) selected="selected" @endif @endif>25</option>
                                        <option value="30" @if($exam_timer) @if($exam_timer[0]['section_test'] == 30) selected="selected" @endif @endif>30</option>
                                        <option value="35" @if($exam_timer) @if($exam_timer[0]['section_test'] == 35) selected="selected" @endif @endif>35</option>
                                        <option value="40" @if($exam_timer) @if($exam_timer[0]['section_test'] == 40) selected="selected" @endif @endif>40</option>
                                        <option value="45" @if($exam_timer) @if($exam_timer[0]['section_test'] == 45) selected="selected" @endif @endif>45</option>
                                        <option value="50" @if($exam_timer) @if($exam_timer[0]['section_test'] == 50) selected="selected" @endif @endif>50</option>
                                    </select>
                                </div>
                                @if ($errors->first('section_test'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('section_test') }}</span>@endif
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Area Test Time</label>
                                <div class="col-md-10 {{ $errors->has('area_test') ? 'has-error' : '' }}">
                                    <select name="area_test" id="area_test" class="form-control">
                                        <option value="10" @if($exam_timer) @if($exam_timer[0]['area_test'] == 10) selected="selected" @endif @endif>10</option>
                                        <option value="15" @if($exam_timer) @if($exam_timer[0]['area_test'] == 15) selected="selected" @endif @endif>15</option>
                                        <option value="20" @if($exam_timer) @if($exam_timer[0]['area_test'] == 20) selected="selected" @endif @endif>20</option>
                                        <option value="25" @if($exam_timer) @if($exam_timer[0]['area_test'] == 25) selected="selected" @endif @endif>25</option>
                                        <option value="30" @if($exam_timer) @if($exam_timer[0]['area_test'] == 30) selected="selected" @endif @endif>30</option>
                                        <option value="35" @if($exam_timer) @if($exam_timer[0]['area_test'] == 35) selected="selected" @endif @endif>35</option>
                                        <option value="40" @if($exam_timer) @if($exam_timer[0]['area_test'] == 40) selected="selected" @endif @endif>40</option>
                                        <option value="45" @if($exam_timer) @if($exam_timer[0]['area_test'] == 45) selected="selected" @endif @endif>45</option>
                                        <option value="50" @if($exam_timer) @if($exam_timer[0]['area_test'] == 50) selected="selected" @endif @endif>50</option>
                                    </select>
                                </div>
                                @if ($errors->first('area_test'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('area_test') }}</span>@endif
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Subject Test Time</label>
                                <div class="col-md-10 {{ $errors->has('subject_test') ? 'has-error' : '' }}">
                                    <select name="subject_test" id="subject_test" class="form-control">
                                        <option value="10" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 10) selected="selected" @endif @endif>10</option>
                                        <option value="15" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 15) selected="selected" @endif @endif>15</option>
                                        <option value="20" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 20) selected="selected" @endif @endif>20</option>
                                        <option value="25" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 25) selected="selected" @endif @endif>25</option>
                                        <option value="30" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 30) selected="selected" @endif @endif>30</option>
                                        <option value="35" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 35) selected="selected" @endif @endif>35</option>
                                        <option value="40" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 40) selected="selected" @endif @endif>40</option>
                                        <option value="45" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 45) selected="selected" @endif @endif>45</option>
                                        <option value="50" @if($exam_timer) @if($exam_timer[0]['subject_test'] == 50) selected="selected" @endif @endif>50</option>
                                    </select>
                                </div>
                                @if ($errors->first('subject_test'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject_test') }}</span>@endif
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Full Exam Test Time</label>
                                <div class="col-md-10 {{ $errors->has('exam_test') ? 'has-error' : '' }}">
                                    <select name="exam_test" id="exam_test" class="form-control">
                                        <option value="60" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 60) selected="selected" @endif  @endif>60</option>
                                        <option value="120" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 120) selected="selected" @endif  @endif>120</option>
                                        <option value="180" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 180) selected="selected" @endif @endif>180</option>
                                        <option value="200" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 200) selected="selected" @endif @endif>200</option>
                                        <option value="220" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 220) selected="selected" @endif @endif>220</option>
                                        <option value="225" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 225) selected="selected" @endif @endif>225</option>
                                        <option value="240" @if($exam_timer) @if($exam_timer[0]['exam_test'] == 240) selected="selected" @endif @endif>240</option>
                                    </select>
                                </div>
                                @if ($errors->first('exam_test'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam_test') }}</span>@endif
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
        </div>
    </div>
    <!-- end #content -->
@endsection