<style type="text/css">
	.my_groups { padding: 8px 20px; line-height: 20px; color: #a8acb1; display: table; }
	.sidebar .nav .active .my-groups { color: #fff; background: #00acac; }
</style>
<div id="sidebar" class="sidebar">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile">
				<div class="image">
					<?php if (empty(Auth::guard('admin')->user()->image)) { ?>
			            <img class="profile-user-img img-responsive img-circle sidebar_image_preview" src="{{ url('/upload/profile_image/default.png')}}" alt="">
		          	<?php } else { ?>
			            <a href="javascript:;"><img class="sidebar_image_preview" src="{{url('upload/profile_image/resize/'.Auth::guard('admin')->user()->image)}}" alt="" /></a>
		          	<?php } ?>
				</div>
				<div class="info m-t-5">
					{{Auth::guard('admin')->user()->first_name}} {{Auth::guard('admin')->user()->last_name}}
					<!-- <small>Front end developer</small> -->
				</div>
			</li>
		</ul>
		<!-- end sidebar user -->
		<!-- begin sidebar nav -->
		<ul class="nav">
			
			<li class="has-sub {{ (Request::is('dashboard') ? 'active' : '') }}">
				<a href="/dashboard">
				    <span>Home</span>
			    </a>
			</li>
			<li class="has-sub {{ (Request::segment(1) === "exam" ? 'active' : '') }}">
				<a href="/exam">
				    <span>Exam</span>
			    </a>
			</li>

			<li class="has-sub {{ (Request::segment(1) === "subject" ? 'active' : '') }}">
				<a href="/subject">
				    <span>Subject</span>
			    </a>
			</li>

			<li class="has-sub {{ (Request::segment(1) === "area" ? 'active' : '') }}">
				<a href="/area">
				    <span>Area</span>
			    </a>
			</li>

			<li class="has-sub {{ (Request::segment(1) === "question" ? 'active' : '') }}">
				<a href="/question">
				    <span>Add Qustion</span>
			    </a>
			</li>

			<li class="has-sub {{ (Request::segment(1) === "tags" ? 'active' : '') }}">
				<a href="/tags">
				    <span>Tags</span>
			    </a>
			</li>

	        <!-- begin sidebar minify button -->
			<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
	        <!-- end sidebar minify button -->
		</ul>
		<!-- end sidebar nav -->
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>