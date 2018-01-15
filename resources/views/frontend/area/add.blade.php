@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

	<!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/area">Area</a></li>
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
                <form name="frmArea" method="POST" action="/area/save" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subject</label>
                        <div class="col-md-10 {{ $errors->has('subject_id') ? 'has-error' : '' }}">
                            <select name="subject_id" id="subject_id" class="form-control">
                                <option value="">Select Subject</option>
                                @foreach($fetch_all_subject as $key => $value )
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->first('subject_id'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('subject_id') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Exam</label>
                        <div class="col-md-10 {{ $errors->has('exam_id') ? 'has-error' : '' }}">
                            <select name="exam_id[]" id="exam_id" class="form-control" multiple>
                                <option value="">Select Exam</option>
                            </select>
                        </div>
                        @if ($errors->first('exam_id'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('exam_id') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Name</label>
                        <div class="col-md-10 {{ $errors->has('name') ? 'has-error' : '' }}">
                            <input class="form-control" placeholder="Area Name" type="text" name="name" id="name" value="{{ old('name') }}">
                        </div>
                        @if ($errors->first('name'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('name') }}</span>@endif
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Description</label>
                        <div class="col-md-10 {{ $errors->has('description') ? 'has-error' : '' }}">
                            <textarea id="description" name="description" placeholder="Write your message here..." class="editor form-control">{{ old('description') }}</textarea>
                        </div>
                        @if ($errors->first('description'))<span class="input-group col-md-offset-2 text-danger">{{ $errors->first('description') }}</span>@endif
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            <a href="/area" class="btn btn-sm btn-default">Cancel</a>
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
            $("#exam_id").select2({
                placeholder: 'Select Exams',
            });

            $("#subject_id").change(function(e) {
                if ($(this).val()) {
                    $.ajax({
                        type: 'POST',
                        url: '/area/get-exam-by-subject',
                        data: {subject_id: $(this).val(), _token: "{{ csrf_token() }}"},
                        success:function(response) {
                            $("#exam_id").find('option').not(':first').remove();
                            for (var i = 0; i < response.exams.length; i++) {
                                $("#exam_id").append('<option value="'+response.exams[i].id+'">'+response.exams[i].name+'</option>');
                            }
                        },
                        error: function(err) {

                        }
                    });
                } else {
                    $("#exam_id").find('option').not(':first').remove();
                }
            });
        });
    </script>
@endsection