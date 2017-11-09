@extends('dashboard_layout')
<!-- end #header -->

<!-- begin #sidebar -->
@section('content')

<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/course">Course</a></li>
		<li><a href="javascript:;">Course Distribution</a></li>
	</ol>

	<!-- begin row -->
	<div class="row">
	    <!-- begin col-12 -->
	    <div class="col-md-12">
            <div class="panel-group" id="accordion">
                @for($i=1; $i<=$total_weeks; $i++)
                    <div class="panel panel-inverse overflow-hidden">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $i }}">
                                    <i class="fa fa-plus-circle pull-right"></i> 
                                    WEEK {{ $i }}
                                </a>
                            </h3>
                        </div>
                        <div id="collapse{{ $i }}" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <ul class="timeline">
                                    <li>
                                        <!-- begin timeline-time -->
                                        <div class="timeline-time">
                                            {{-- <span class="date">today</span>
                                            <span class="time">04:20</span> --}}
                                        </div>
                                        <!-- end timeline-time -->
                                        <!-- begin timeline-icon -->
                                        <div class="timeline-icon">
                                            <a href="javascript:;"><i class="fa fa-paper-plane"></i></a>
                                        </div>
                                        <!-- end timeline-icon -->
                                        <!-- begin timeline-body -->
                                        <div class="timeline-body">
                                            <div class="timeline-header">
                                                <span class="userimage"><img src="assets/img/user-1.jpg" alt="" /></span>
                                                <span class="username"><a href="javascript:;">{{-- {{ $tempArray['topic_name'] }} --}}</a> <small></small></span>
                                            </div>
                                            <div class="timeline-content">
                                                <p>
                                                    {{-- {{ $tempArray['topic_description'] }} --}}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- end timeline-body -->
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endfor
                
            </div>
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>


@endsection