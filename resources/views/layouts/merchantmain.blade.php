<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Apoyou Merchants</title>

  <!-- Bootstrap -->
  <link href="{{ asset('admin-assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ asset('admin-assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  <!-- NProgress -->
  <link href="{{ asset('admin-assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="{{ asset('admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="{{ asset('admin-assets/build/css/custom.min.css') }}" rel="stylesheet">
  <!-- Datatables -->
  <link href="{{ asset('admin-assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/dropzone/dropzone.css') }}">

  <!-- Select2 -->
  <link href="{{ asset('admin-assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
  <!-- Switchery -->
  <link href="{{ asset('admin-assets/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/rangeslider.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
  <!-- jQuery -->
  <script src="{{ asset('admin-assets/vendors/jquery/dist/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/dropzone/dropzone.min.js') }}"></script>
  <script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/js/rangeslider.js') }}"></script>
  <script src="{{ asset('assets/js/rangeslider.min.js') }}"></script>
  <script type="text/javascript">
   var asset_path = "{{ url('') }}";
   tinymce.init({
    selector: ".tinymce",theme: "modern",height: 200,
    plugins: [
    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
    "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
    ],
    relative_urls : false,
    menubar: false,
    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
    toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
    image_advtab: false ,
    external_filemanager_path:"{{ url('') }}/file_manager/filemanager/",
    filemanager_title:"Responsive Filemanager" ,
    external_plugins: { "filemanager" : "{{ url('') }}/file_manager/filemanager/plugin.min.js"}
  });
</script>
<style>
.image_loader{
  display:none;
  position: absolute;
  top: 50%;
  left: 50%;
  z-index: 999;
}
.form-group.required .control-label:after {
 content:"*";
 color:red;
}
</style>
@yield('head')
</head>

<?php
$user_id = $merchant_user->id;
$merchant_status = $merchant_user->merchant_status;
$last_subscription = $merchant_user->last_subscription;
?>

<body class="nav-md"><img id="loading" class="image_loader" src="{{ url('') }}/admin-assets/images/3.gif" />
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="{{url('merchant')}}" class="site_title"><i class="fa fa-paw"></i> <span>Merchant!</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="{{ url('') }}/uploads/thumbnail/{{ Auth::user()->image }}"" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2>{{ Auth::user()->first_name }}</h2>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
               <li><a href="{{ url('merchant') }}"><i class="fa fa-home"></i> Dashboard </a></li>
               <li><a href="{{ url('merchant/listing') }}"><i class="fa fa-list"></i>Listing </a></li>
               <li><a href="{{ url('merchant/services') }}"><i class="fa fa-archive"></i>Services </a></li>
               <li><a href="{{ url('merchant/subscription-history') }}"><i class="fa fa-archive"></i>My Subscriptions </a></li>
               <li><a href="{{ url('merchant/change-subscription-plan') }}"><i class="fa fa-archive"></i>Change My Plan </a></li>
               <li><a href="{{ url('campaign') }}"><i class="fa fa-archive"></i>Campaigns </a></li>
               <li><a href="{{ url('merchant/allreviews') }}"><i class="fa fa-archive"></i>Reviews </a></li>
               @if($enable_services)
				   @foreach ($all_services as $service)
					   @php
					   $merchant_service = $service->merchant_service()->where('merchant_id', $user_id)->first();
					   $merchant_service_id = '';
					   if($merchant_service)
					   $merchant_service_id = $merchant_service->id;
					   @endphp
				   @if( isset($merchant_service->is_enable) and $merchant_service->is_enable)
					   @if($service->id=='3')
					   <li><a><i class="fa fa-list"></i> {{ $service->name }}  <span class="fa fa-chevron-down"></span></a>
						 <ul class="nav child_menu">
						   <li><a href="{{ url('merchant') . '/' . $service->slug }}">Add Setting</a></li>
						   <li><a href="{{url('merchant/allbookings')}}">All Bookings </a></li>                    
						 </ul>
					   </li>
					   @endif
					   @if($service->id=='4')
					   <li><a><i class="fa fa-list"></i> {{ $service->name }}  <span class="fa fa-chevron-down"></span></a>
						 <ul class="nav child_menu">
						   <li><a href="{{ url('merchant') . '/' . $service->slug }}">Add Setting</a></li>
						   <li><a href="{{url('merchant/allappointments')}}">All Appointments </a></li>                    
						 </ul>
					   </li>
					   @endif
					   @if($service->id=='1')
					   @php
					   $active = '';
					   $display = '';
					   if( strpos(Request::url(), 'merchant/online-order/booking-customers') !== false)
					   {
						 $active = 'active';
						 $display = ' style=display:block;';
					   }
					   @endphp
					   <li class="{{$active}}"><a><i class="fa fa-list"></i> {{ $service->name }}  <span class="fa fa-chevron-down"></span></a>
						 <ul class="nav child_menu"{{$display}}>
						   <li><a href="{{ url('merchant/online-order/add-menu') }}">Add Menu Category</a></li>
						   <li><a href="{{url('merchant/online-order/menu-lists')}}">My Menu Categories </a></li> 
						   <li><a href="{{url('merchant/online-order/menu-items')}}">Menu Items </a></li>
						   <li><a href="{{url('merchant/online-order/booking-customers')}}">Booking Customers </a></li> 
						   <li><a href="{{url('merchant/online-order/settings')}}">Settings </a></li>               
						 </ul>
					   </li>
						@endif
					   @if($service->id=='2')
					   <li><a><i class="fa fa-list"></i> {{ $service->name }}  <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li>
								  <a href="javascript:;" class="">
								   <i class="icon_document_alt"></i>
								   Products<span class="fa fa-plus"></span>
								 </a>
								 <ul class="nav child_menu">
								   <li><a href="{{url('/merchant/addProduct')}}">Add Products</a></li>
								   <li><a href="{{url('/merchant/products')}}">View Products</a></li>
								 </ul>
								</li>
								<li>
								<a href="javascript:;" class="">
								 <i class="icon_desktop"></i>
								 Categories<span class="fa fa-plus"></span>
								</a>
								<ul class="nav child_menu">
								 <li><a class="" href="{{url('/merchant/addCat')}}">Add Category</a></li>
								 <li><a class="" href="{{url('/merchant/categories')}}">View Categories</a></li>

								</ul>
								</li>
								<li><a href="{{url('merchant/online-order/booking-customers')}}">Customer Orders</a></li>
								<?php /*<li><a href="{{url('merchant/online-order/settings')}}">Settings </a></li>*/?>
							</ul>
						</li>
						@endif
					@endif	
				@endforeach
			@endif	

         <li><a href="{{ url('merchant/payment-settings') }}"><i class="fa fa-archive"></i>Payment Settings </a></li>
       </ul>
     </div>
   </div>
   <!-- /sidebar menu -->

   <!-- /menu footer buttons -->
   <div class="sidebar-footer hidden-small">
    <?php /*<a data-toggle="tooltip" data-placement="top" title="Settings">
      <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
      <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
      <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>*/?>
    <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('logout') }}">
      <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
  </div>
  <!-- /menu footer buttons -->
</div>
</div>

<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img src="{{ url('') }}/uploads/thumbnail/{{ Auth::user()->image }}" alt="">		
           {{ Auth::user()->first_name }}
           <span class=" fa fa-angle-down"></span>
         </a>
         <ul class="dropdown-menu dropdown-usermenu pull-right">
          <?php /*<li><a href="javascript:;"> Profile</a></li>
          <li>
            <a href="javascript:;">
              <span class="badge bg-red pull-right">50%</span>
              <span>Settings</span>
            </a>
          </li>
          <li><a href="javascript:;">Help</a></li>*/?>
		  <li><a href="{{ url('/') }}" target="_blank">View Site</a></li>
          <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
        </ul>
      </li>

      <li role="presentation" class="dropdown">
        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-envelope-o"></i>
          <span class="badge bg-green notification_count"></span>
        </a>
        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">


        </ul>
      </li>
    </ul>
  </nav>
</div>
</div>
<!-- /top navigation -->

<!-- page content -->
<div class="right_col" role="main">
	<?php /*
	@include('partials.above-navbar-alert')
	*/?>
	@if($show_content_type == 'content')
		@yield('content')
	@elseif($show_content_type == 'inactive')
		@include('partials.alert_merchant_inactive')
	@else
		@include('partials.alert_merchant_pending')
	@endif
</div>
<!-- /page content -->

<!-- footer content -->
<footer>
  <div class="pull-right">
    <a href="https://www.apoyou.com/">Apoyou.com</a>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /footer content -->
</div>
</div>
@yield('footer')

<!-- Bootstrap -->
<script src="{{ asset('admin-assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('admin-assets/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('admin-assets/vendors/nprogress/nprogress.js') }}"></script>
<!-- Chart.js -->
<script src="{{ asset('admin-assets/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- jQuery Sparklines -->
<script src="{{ asset('admin-assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- Flot -->
<script src="{{ asset('admin-assets/vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('admin-assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<!-- DateJS -->
<script src="{{ asset('admin-assets/vendors/DateJS/build/date.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('admin-assets/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Custom Theme Scripts -->
<script>
  var today_date = "<?php echo date('m/d/Y');?>";
</script>
<script src="{{ asset('admin-assets/build/js/custom.js') }}"></script>
<!-- Datatables -->
<script src="{{ asset('admin-assets/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/pdfmake/build/vfs_fonts.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('admin-assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>
<!-- Switchery -->
<script src="{{ asset('admin-assets/vendors/switchery/dist/switchery.min.js') }}"></script>
<script>
  $(document).ready(function(){
    var notification='';
    $('.msg_list').html('');
    $.ajax({
      type:'get',
      url:"{{url('')}}/merchant/notification",
      dataType:"json",
      success:function(res){
        $('.notification_count').html(res.length);
        
        if(res.length>0){
          $.each(res,function(key,value){
            notification+='<li>';
            if(value.type=='Appointment'){
              notification+='<a class="read_status"  data-notid="'+value.id+'" href="{{url('')}}/merchant/allappointments">';
            }
            else if(value.type=='Table'){
              notification+='<a class="read_status" data-notid="'+value.id+'" href="{{url('')}}/merchant/allbookings" >';
            }
            else if(value.type=='Review'){
              notification+='<a class="read_status" data-notid="'+value.id+'" href="{{url('')}}/merchant/allreviews" >';
            }
            else{
             notification+='<a class="read_status"  data-notid="'+value.id+'" href="{{url('')}}/merchant/customer-orders">';
           }

           notification+='<span class="image"><img src="{{ asset('admin-assets/images/img.jpg') }}" alt="Profile Image" /></span>\
           <span>\
           <span>'+value.name+'</span>\
           <span class="time">'+value.time+'</span>\
           </span>\
           <span class="message">\
           '+value.message+'\
           </span>\
           </a>\
           </li>';
           if(key==4){
           return false;
           }
         });

          notification+='<li>\
          <div class="text-center">\
          <a href="{{url('')}}/merchant/allnotifications">\
          <strong>See All Alerts</strong>\
          <i class="fa fa-angle-right"></i>\
          </a>\
          </div>\
          </li>';
        }
        else{
          notification+='<li>No new notifications</li>';
        }
        $('.msg_list').append(notification);
      }
    });
    $(document).on('click','.read_status',function(){
      var href=$(this).attr('href');
      var id=$(this).data('notid');
      change_status(id);
      window.location.href=href;
    });
    function change_status(id){
     $.ajax({
      type:'get',
      url:"{{url('')}}/merchant/read_status",
      dataType:"json",
      data:{'id':id},
      success:function(res){

      },
      error:function(){
        //alert('dfd');
      }
    });
   }
 });
</script>
</body>
</html>