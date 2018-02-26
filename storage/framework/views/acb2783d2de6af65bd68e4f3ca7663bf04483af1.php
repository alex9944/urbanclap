<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Admin Panel</title>

  <!-- Bootstrap -->
  <link href="<?php echo e(asset('admin-assets/vendors/bootstrap/dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="<?php echo e(asset('admin-assets/vendors/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">
  <!-- NProgress -->
  <link href="<?php echo e(asset('admin-assets/vendors/nprogress/nprogress.css')); ?>" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="<?php echo e(asset('admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.css')); ?>" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="<?php echo e(asset('admin-assets/build/css/custom.min.css')); ?>" rel="stylesheet">
  <!-- Datatables -->
  <link href="<?php echo e(asset('admin-assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin-assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin-assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin-assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin-assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin-assets/css/croppic.css')); ?>" rel="stylesheet">
  <!-- Select2 -->
  <link href="<?php echo e(asset('admin-assets/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
  <!-- Switchery -->
  <link href="<?php echo e(asset('admin-assets/vendors/switchery/dist/switchery.min.css')); ?>" rel="stylesheet">
  <!-- jQuery -->
  <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
  <!-- <script src="<?php echo e(asset('admin-assets/vendors/jquery/dist/jquery.min.js')); ?>"></script> -->
  <script src="<?php echo e(asset('tinymce/js/tinymce/tinymce.min.js')); ?>"></script>
  <script type="text/javascript">
			var asset_path = "<?php echo e(url('')); ?>"; //alert(asset_path);
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
				external_filemanager_path:"<?php echo e(url('')); ?>/file_manager/filemanager/",
				filemanager_title:"Responsive Filemanager" ,
				external_plugins: { "filemanager" : "<?php echo e(url('')); ?>/file_manager/filemanager/plugin.min.js"}
		  });
		</script>
		<script  type="text/javascript" src="<?php echo e(asset('admin-assets/js/typeahead.bundle.js')); ?>"></script>
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
<style>
.enable{background-color: #2d7117 !important;border-color: #2d7117 !important;}
.disable{background-color: #b5b5b5 !important;border-color: #b5b5b5 !important;}
</style>
	 <?php echo $__env->yieldContent('head'); ?>
  </head>

<body class="nav-md"><img id="loading" class="image_loader" src="<?php echo e(url('')); ?>/admin-assets/images/3.gif" />
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="javascript:void(0);" class="site_title"><span>Admin Panel!</span></a>
          </div>

          <div class="clearfix"></div>

         

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              	<br />
			<h3 style="color:#d2b800">General</h3>
              <ul class="nav side-menu">
                <li><a href="<?php echo e(url('admin')); ?>"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="<?php echo e(url('admin/users')); ?>"><i class="fa fa-desktop"></i>Admin </a></li>
                <li><a href="<?php echo e(url('admin/category')); ?>"><i class="fa fa-list"></i> Category</a></li>
                <li><a href="<?php echo e(url('admin/services')); ?>"><i class="fa fa-list"></i> Services</a></li>
			   <li><a href="<?php echo e(url('admin/questions')); ?>"><i class="fa fa-bar-chart"></i>Questionnaire</span></a></li>
				<!--<li><a><i class="fa fa-th-large"></i>Subscription  <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">				
                 <li><a href="<?php echo e(url('admin/subscription/plan')); ?>">Plan</a></li>
                 <li><a href="<?php echo e(url('admin/subscription/duration')); ?>">Duration</a></li>
                 <li><a href="<?php echo e(url('admin/subscription/features')); ?>">Features</a></li>
                 <li><a href="<?php echo e(url('admin/subscription/pricing')); ?>">Pricing</a></li>
               </ul>
             </li>-->
            	
				<br />
			<h3 style="color:#d2b800">Users</h3>
			   <li class=""><a><i class="fa fa-building-o"></i>Vendors  <span class="fa fa-chevron-down"></span></a>
              <?php /*<ul class="nav child_menu">
                <li><a href="{{ url('admin/merchants/subscription-history') }}">Subscription History</a></li>
				<li><a href="{{ url('admin/merchants/users') }}">Users</a></li>             
              </ul>*/?>
            </li>
            
              
             
                

                <li><a><i class="fa fa fa-users"></i>General <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <?php /*<li><a href="{{ url('admin/general/users') }}">Users</a></li>
                    <li><a href="fixed_footer.html">Fixed Footer</a></li> */?>
                  </ul>
                </li>
				<br />
			<h3 style="color:#d2b800">All Reports</h3>
                <li><a><i class="fa fa-bar-chart"></i>Reports <span class="fa fa-chevron-down"></span></a>
                  <?php /*<ul class="nav child_menu">
                    <li><a href="{{url('admin/reports/onlineorder')}}">Online Order</a></li>
                    <li><a href="{{url('admin/reports/onlineshoping')}}">Online Shoping</a></li>
                  </ul>*/?>
                </li>



              </ul>
            </div>
            <div class="menu_section">
             	<br />
			<h3 style="color:#d2b800">CMS</h3>
              <ul class="nav side-menu">

                <li><a><i class="fa fa-windows"></i> CMS Pages  <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <?php /*<li><a href="{{ url('admin/menutype') }}">Menu Type</a></li>*/?>
                    <li><a href="<?php echo e(url('admin/menu')); ?>">Menus</a></li>
                    <li><a href="<?php echo e(url('admin/page')); ?>">Dynamic Pages</a></li>
                  </ul>
                </li>
                <li><a href="<?php echo e(url('admin/blog')); ?>"><i class="fa fa-sitemap"></i> Blog </a>

                </li>   
                <li><a href="<?php echo e(url('admin/news')); ?>"><i class="fa fa-newspaper-o"></i> News </a>

                </li>                 
                <li><a href="<?php echo e(url('admin/testimonials')); ?>"><i class="fa fa-frown-o"></i> Testimonials </a>

                </li> 				  

              </ul>
            </div>

          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small">
            <a href="<?php echo e(url('admin/settings')); ?>" data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo e(url('logout')); ?>">
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
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                 <?php echo e(Auth::user()->first_name); ?>

                 <span class=" fa fa-angle-down"></span>
               </a>
               <ul class="dropdown-menu dropdown-usermenu pull-right">
                <li class="hidden"><a href="javascript:;"> Profile</a></li>
                <li class="hidden">
                  <a href="javascript:;">
                    <span class="badge bg-red pull-right">50%</span>
                    <span>Settings</span>
                  </a>
                </li>
                <li><a href="<?php echo e(url('/')); ?>" target="_blank">View Site</a></li>
                <li><a href="<?php echo e(url('logout')); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
              </ul>
            </li>

            
          </ul>
        </nav>
      </div>
    </div>
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main">
      <?php echo $__env->make('partials.above-navbar-alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <?php echo $__env->yieldContent('content'); ?>

    </div>
    <!-- /page content -->

    <!-- footer content -->
    <footer>
      <div class="pull-right">      
      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
  </div>
</div>
<?php echo $__env->yieldContent('footer'); ?>

<!-- Bootstrap -->
<script src="<?php echo e(asset('admin-assets/vendors/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
<!-- FastClick -->
<script src="<?php echo e(asset('admin-assets/vendors/fastclick/lib/fastclick.js')); ?>"></script>
<!-- NProgress -->
<script src="<?php echo e(asset('admin-assets/vendors/nprogress/nprogress.js')); ?>"></script>
<!-- Chart.js -->
<script src="<?php echo e(asset('admin-assets/vendors/Chart.js/dist/Chart.min.js')); ?>"></script>
<!-- jQuery Sparklines -->
<script src="<?php echo e(asset('admin-assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js')); ?>"></script>
<!-- Flot -->
<script src="<?php echo e(asset('admin-assets/vendors/Flot/jquery.flot.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/Flot/jquery.flot.pie.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/Flot/jquery.flot.time.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/Flot/jquery.flot.stack.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/Flot/jquery.flot.resize.js')); ?>"></script>
<!-- Flot plugins -->
<script src="<?php echo e(asset('admin-assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/flot-spline/js/jquery.flot.spline.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/flot.curvedlines/curvedLines.js')); ?>"></script>
<!-- DateJS -->
<script src="<?php echo e(asset('admin-assets/vendors/DateJS/build/date.js')); ?>"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo e(asset('admin-assets/vendors/moment/min/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>

<!-- Custom Theme Scripts -->
<script src="<?php echo e(asset('admin-assets/build/js/custom.js')); ?>"></script>
<!-- Datatables -->
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-buttons/js/buttons.flash.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/jszip/dist/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/vendors/pdfmake/build/vfs_fonts.js')); ?>"></script>

<!-- Select2 -->
<script src="<?php echo e(asset('admin-assets/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
<!-- Switchery -->
<script src="<?php echo e(asset('admin-assets/vendors/switchery/dist/switchery.min.js')); ?>"></script>

<script src="<?php echo e(asset('admin-assets/js/jquery.bootstrap.wizard.js')); ?>"></script>

<script src="<?php echo e(asset('admin-assets/js/jquery.mousewheel.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/js/croppic.js')); ?>"></script>
<script>
  var CSRF_TOKEN = '<?php echo e(csrf_token()); ?>';
</script>

    </body>
    </html>