<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Mirrored from seantheme.com/color-admin-v3.0/admin/html/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 15 Sep 2017 12:24:45 GMT -->
<head>
	<meta charset="utf-8" />
  
	<title>LMS Admin | Dashboard</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	{!! Html::style('storage/admin_dashboard/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/plugins/bootstrap/css/bootstrap.min.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/plugins/font-awesome/css/font-awesome.min.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/css/animate.min.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/css/style.min.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/css/style-responsive.min.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/css/theme/default.css') !!}

  {!! Html::style('storage/admin_dashboard/assets/css/chats.css') !!}
	
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	{!! Html::style('storage/admin_dashboard/assets/plugins/jquery-jvectormap/jquery-jvectormap.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') !!}
  {!! Html::style('storage/admin_dashboard/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') !!}
	{!! Html::style('storage/admin_dashboard/assets/plugins/gritter/css/jquery.gritter.css') !!}
  {!! Html::style('storage/admin_dashboard/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
  {!! Html::style('storage/admin_dashboard/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') !!}
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	{!! Html::script('storage/admin_dashboard/assets/plugins/pace/pace.min.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/plugins/jquery/jquery-1.9.1.min.js') !!}

  
  
  {!! Html::style('storage/admin_dashboard/assets/css/bootstrap-multiselect.css') !!}
  {!! Html::script('storage/admin_dashboard/assets/js/bootstrap-multiselect.js') !!}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

  {!! Html::script('storage/admin_dashboard/assets/plugins/jquery/jquery-migrate-1.1.0.min.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/plugins/flot/jquery.flot.min.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/plugins/flot/jquery.flot.time.min.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/plugins/flot/jquery.flot.resize.min.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/plugins/flot/jquery.flot.pie.min.js') !!}

  {!! Html::script('storage/admin_dashboard/assets/js/jquery.waterwheelCarousel.js') !!}

  {!! Html::script('storage/admin_dashboard/assets/ckeditor/resources/libs/ckeditor/ckeditor.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/ckeditor/resources/libs/ckeditor/adapters/jquery.js') !!}
  {!! Html::script('storage/admin_dashboard/assets/ckeditor/resources/js/index.js') !!}
  
	<!-- ================== END BASE JS ================== -->
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		@include('partial/dashboard_header')
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		@include('partial/dashboard_sidebar')
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		@yield('content')
		<!-- end #content -->
		
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->


	
	<!-- ================== BEGIN BASE JS ================== -->

	{!! Html::script('storage/admin_dashboard/assets/plugins/bootstrap/js/bootstrap.min.js') !!}

    {!! Html::script('storage/admin_dashboard/assets/plugins/DataTables/media/js/jquery.dataTables.js') !!}
    {!! Html::script('storage/admin_dashboard/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('storage/admin_dashboard/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js') !!}
    {!! Html::script('storage/admin_dashboard/assets/js/table-manage-default.demo.min.js') !!}
	
	<!--[if lt IE 9]>
		<script src="storage/admin_dashboard/assets/crossbrowserjs/html5shiv.js"></script>
		<script src="storage/admin_dashboard/assets/crossbrowserjs/respond.min.js"></script>
		<script src="storage/admin_dashboard/assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	{!! Html::script('storage/admin_dashboard/assets/plugins/slimscroll/jquery.slimscroll.min.js') !!}
	{!! Html::script('storage/admin_dashboard/assets/plugins/jquery-cookie/jquery.cookie.js') !!}
	
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
    {!! Html::script('storage/admin_dashboard/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
	{!! Html::script('storage/admin_dashboard/assets/plugins/gritter/js/jquery.gritter.js') !!}
	{!! Html::script('storage/admin_dashboard/assets/plugins/sparkline/jquery.sparkline.js') !!}

	{!! Html::script('storage/admin_dashboard/assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js') !!}
	{!! Html::script('storage/admin_dashboard/assets/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js') !!}

	{!! Html::script('storage/admin_dashboard/assets/js/dashboard.min.js') !!}
    {!! Html::script('storage/admin_dashboard/assets/js/form-plugins.demo.min.js') !!}
	{!! Html::script('storage/admin_dashboard/assets/js/apps.min.js') !!}

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>

    {!! Html::script('storage/admin_dashboard/assets/js/apps.min.js') !!}

	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
        $('.date').datepicker();
        
  			App.init();
  			Dashboard.init();
        TableManageDefault.init();

        CKEDITOR.replace('course_description', {
            height: 260,
            width: 880,
        } ); 

        $('#edit_profile_form').validate({
          rules:{
            f_name:{
              required:true
            },
            l_name:{
              required:true
            },
            mobile:{
              required:true,
              number:true,
              maxlength:10,
              minlength:10
            },
            address:{
              required:true
            }
          },
          messages:{
            f_name:{
              required:"<font color='red'>First name can't be left blank.</font>"
            },
            l_name:{
              required:"<font color='red'>Last name can't be left blank.</font>"
            },
            mobile:{
              required:"<font color='red'>Mobile number can't be left blank.</font>",
              number:"<font color='red'>Please enter valid mobile number.</font>",
              maxlength:"<font color='red'>Mobile number should be 10 digit.</font>",
              minlength:"<font color='red'>Mobile number should be 10 digit.</font>"
            },
            address:{
              required:"<font color='red'>Address can't be left blank.</font>"
            }
          }
        });

        $('#edit_profile_submit').on('click',function(){
          var valid = $('#edit_profile_form').valid();
          if(valid){
            $('#edit_profile_form').submit();
          }
        });

        setTimeout(function() {
          $("#success-alert").hide('blind', {}, 500)
        }, 2000);
            
		});
	</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53034621-1', 'auto');
  ga('send', 'pageview');


//for profile image preview & size validation

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('.image_preview').attr('src', e.target.result);
                $('.header_image_preview').attr('src', e.target.result);
                $('.sidebar_image_preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(".profile_image").change(function(){
        readURL(this);
    });

    $("#image").change(function(){
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('.img-team').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    });

     $(document).on('change','.profile_image',function(){
          files = this.files;
          size = files[0].size;
          //max size 50kb => 50*1000
          if( size > 2000000){
             alert('Please upload less than 2MB file');
             return false;
          }
          return true;
     });

</script>

</body>

</html>
