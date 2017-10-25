<div id="header" class="header navbar navbar-default navbar-fixed-top">
	<!-- begin container-fluid -->
	<div class="container-fluid">
		<!-- begin mobile sidebar expand / collapse button -->
		<div class="navbar-header">
			<a href="/dashboard" class="navbar-brand"><img src="{{url('/storage/frontend/assets/img/logo.png')}}" alt="CryptShares" width="200" /></a>
			<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<!-- end mobile sidebar expand / collapse button -->
		
		<!-- begin header navigation right -->
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown navbar-user">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <?php if (empty(Auth::guard('admin')->user()->image)) { ?>
                        <img class="profile-user-img img-responsive img-circle header_image_preview" src="{{ url('/upload/profile_image/default.png')}}" alt="">
                    <?php } else { ?>
                        <img class="header_image_preview" src="{{url('upload/profile_image/resize/'.Auth::guard('admin')->user()->image)}}" alt="" />
                    <?php } ?>
					<span class="hidden-xs">{{Auth::guard('admin')->user()->first_name}} {{Auth::guard('admin')->user()->last_name}}</span> <b class="caret"></b>
				</a>
				<ul class="dropdown-menu animated fadeInLeft">
					<li class="arrow"></li>
					<li><a href="/profile-view">Profile</a></li>
					<li><a href="/change_pass">Change Password</a></li>
					<li class="divider"></li>
					<li><a href="/logout">Log Out</a></li>
				</ul>
			</li>
		</ul>
		<!-- end header navigation right -->
	</div>
	<!-- end container-fluid -->
</div>