<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php /*
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="">
            <div class="row top_tiles">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                  <div class="count">
                  <h3>Users</h3>
				  <h5>Total : {{$users_count_total}}</h5>
                  <h5>Last 7 days : {{$users_count}}</h5>
				  </div>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-comments-o"></i></div>
                  <div class="count">
                  <h3>Merchants</h3>
				  <h5>Total : {{$merchants_count_total}}</h5>
                  <h5>Last 7 days : {{$merchants_count}}</h5>
                </div>
				</div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                  <div class="count">
                  <h3>Non Verified Merchants</h3>
				  <h5>Total : {{$non_verified_merchants_count_total}}</h5>
                  <!--<p>Lorem ipsum psdea itgum rixt.</p> -->
                </div>
				</div>
              </div>
			
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check-square-o"></i></div>
			
                  <div class="count">
                  <h3>Sales</h3>
				  <h5>Total:{{$orders_total}} Orders / Rs.{{$orders_amount}}</h5>
                  <h5>Last 7 days :{{$orders_total_weeks}} Orders / Rs.{{$orders_amount_weeks}}</h5>
                </div>
				</div>
              </div>
            </div>
          
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Transaction Summary <small>Weekly progress</small></h2>
                    <div class="filter">
                      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div id="sales_section" class="col-md-9 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:280px">
                        <div id="sales_bar" style="width:100%; height:280px;"></div>
                      </div>
                      <div class="tiles">
                       <!-- <div class="col-md-4 tile">
                          <span>Total Sessions</span>
                          <h2>231,809</h2>
                          <span class="sparkline11 graph" style="height: 160px;">
                               <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                          </span> 
                        </div> -->
                        <div class="col-md-4 tile">
                          <span>Total Revenue</span>
                          <h2>{{$currency->code.' '.$total_revenue}}</h2>
                          <span class="sparkline_sales graph" style="height: 160px;">
                                <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                          </span> 
                        </div>
                        <div class="col-md-4 tile">
                          <span>Total Orders</span>
                          <h2>{{$total_no_of_orders}}</h2>
                          <!--<span class="sparkline11 graph" style="height: 160px;">
                                 <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas> -->
                          </span>
                        </div>
                      </div>

                    </div>


                  </div>
                </div>
              </div>
            </div>



            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Traffic For Lisiting </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                     <!-- <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li> -->
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row" style="border-bottom: 1px solid #E0E0E0; padding-bottom: 5px; margin-bottom: 5px;">
                      <div class="col-md-7 hidden" style="overflow:hidden;">
                        <span class="sparkline_one" style="height: 160px; padding: 10px 25px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                        <h4 style="margin:18px">Weekly sales progress</h4>
                      </div>

                      <div class="col-md-5">
                        <div class="row" style="text-align: center;">
                          <!--<div class="col-md-4">
                            <canvas class="canvasDoughnut" height="110" width="110" style="margin: 5px 10px 10px 0"></canvas>
                            <h4 style="margin:0">Bounce Rates</h4>
                          </div> -->
                          <div class="col-md-6">
                            <canvas class="canvasDoughnut_traffic" height="110" width="110" style="margin: 5px 10px 10px 0"></canvas>
                            <h4 style="margin:0">New Vs Existing User</h4>
                          </div>
                          <div class="col-md-6">
                            <canvas class="canvasDoughnut_device" height="110" width="110" style="margin: 5px 10px 10px 0"></canvas>
                            <h4 style="margin:0">Device Share</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <div class="row">
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><a href="{{url('/admin/merchants/users')}}">Pending Merchant Profiles</a></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    @foreach($pending_profiles as $pending_profile)
					<article class="media event">
                      <a class="pull-left date">
                        <p class="month"><?php echo date('M', strtotime($pending_profile->created_at));?></p>
							<p class="day"><?php echo date('j', strtotime($pending_profile->created_at));?></p>
                      </a>
                      <div class="media-body">
                        <a class="title">{{$pending_profile->first_name}}</a>
                        <p>Email: {{$pending_profile->email}}, Phone: {{$pending_profile->mobile_no}}, City: {{$pending_profile->city_name}}</p>
                      </div>
                    </article>
                    @endforeach
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Recent Subscriptions</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    @foreach($recent_subscriptions as $recent_subscription)
						<?php
							$plan_detail = json_decode($recent_subscription->plan_detail);
							$price = $plan_detail->subscription_pricing->price;
							if($recent_subscription->currency_id)
								$price = $recent_subscription->currency->symbol.$price;
							$price = 'Price: '.$price;
							$plan = $plan_detail->subscription_pricing->plan.' Plan, '.$price.' For '.$plan_detail->subscription_term->display_value;
							$category = 'Category: '.$plan_detail->subscription_category->title;
						?>
						<article class="media event">
						  <a class="pull-left date">
							<p class="month"><?php echo date('M', strtotime($recent_subscription->subscribed_date));?></p>
							<p class="day"><?php echo date('j', strtotime($recent_subscription->subscribed_date));?></p>
						  </a>
						  <div class="media-body">
							<a class="title" href="#">{{isset($recent_subscription->merchant->first_name) ? $recent_subscription->merchant->first_name : ''}}</a>
							<p>{{$plan}}</p>
							<p>{{$category}}</p>
						  </div>
						</article>
					@endforeach
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Recent Renewals</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
<script src="{{ asset('admin-assets/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('admin-assets/morris.js/morris.min.js') }}"></script>
<script>
$(document).ready(function() {
	
	init_daterangepicker();
	
	<?php if($labels_graph_bar):?>
	if ($('#sales_bar').length){ 
			
		Morris.Bar({
		  element: 'sales_bar',
		  data: [
			<?php echo $labels_graph_bar;?>
		  ],
		  xkey: 'day',
		  ykeys: ['order_amount'],
		  labels: ['Order Amount'],
		  barRatio: 0.4,
		  barColors: ['#005952', '#34495E', '#ACADAC', '#3498DB'],
		  xLabelAngle: 35,
		  hideHover: 'auto',
		  resize: true
		});

	}
	<?php endif;?>
	
	<?php if($price_bar):?>
	$(".sparkline_sales").sparkline([<?php echo $price_bar;?>], {
		type: 'line',
		height: '40',
		width: '200',
		lineColor: '#26B99A',
		fillColor: '#ffffff',
		lineWidth: 3,
		spotColor: '#34495E',
		minSpotColor: '#34495E'
	});
	<?php endif;?>
	
	<?php if($traffic_data['labels']):?>
	var chart_doughnut_settings = {
		type: 'doughnut',
		tooltipFillColor: "rgba(51, 51, 51, 0.55)",
		data: {
			labels: [<?php echo $traffic_data['labels'];?>],
			datasets: [{
				data: [<?php echo $traffic_data['data'];?>],
				backgroundColor: [<?php echo $traffic_data['color'];?>],
				hoverBackgroundColor: [<?php echo $traffic_data['hover_color'];?>]
			}]
		},
		options: { 
			legend: false, 
			responsive: false 
		}
	}
	$('.canvasDoughnut_traffic').each(function(){
		
		var chart_element = $(this);
		var chart_doughnut = new Chart( chart_element, chart_doughnut_settings);
		
	});	
	<?php endif;?>
	
	<?php if($device_data['labels']):?>
	var chart_doughnut_settings = {
		type: 'doughnut',
		tooltipFillColor: "rgba(51, 51, 51, 0.55)",
		data: {
			labels: [<?php echo $device_data['labels'];?>],
			datasets: [{
				data: [<?php echo $device_data['data'];?>],
				backgroundColor: [<?php echo $device_data['color'];?>],
				hoverBackgroundColor: [<?php echo $device_data['hover_color'];?>]
			}]
		},
		options: { 
			legend: false, 
			responsive: false 
		}
	}
	$('.canvasDoughnut_device').each(function(){
		
		var chart_element = $(this);
		var chart_doughnut = new Chart( chart_element, chart_doughnut_settings);
		
	});	
	<?php endif;?>
});
function init_daterangepicker() {

	if( typeof ($.fn.daterangepicker) === 'undefined'){ return; }
	console.log('init_daterangepicker');

	var cb = function(start, end, label) {
		//console.log(start.toISOString(), end.toISOString(), label);
		$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		
		var start_date = start.format('YYYY-MM-D');
		var end_date = end.format('YYYY-MM-D');
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id');
	
		var host="{{ url('admin/get_order_graph') }}";
		$.ajax({
			type: 'POST',
			data:{'start_date': start_date, 'end_date': end_date,'_token':CSRF_TOKEN},
			url: host,
			dataType: "html", // data type of response		
			beforeSend: function(){
				$('.image_loader').show();
			},
			complete: function(){
				$('.image_loader').hide();
			},
			success: function(res)
			{
				$('#sales_section').html(res);
			}
           
		});
	};
	var today_date = new Date();
	var optionSet1 = {
	  startDate: moment().subtract(6, 'days'),
	  endDate: moment(),
	  minDate: '01/01/2016',
	  maxDate: today_date,//'12/31/2015',
	  dateLimit: {
		days: 60
	  },
	  showDropdowns: true,
	  showWeekNumbers: true,
	  timePicker: false,
	  timePickerIncrement: 1,
	  timePicker12Hour: true,
	  ranges: {
		//'Today': [moment(), moment()],
		//'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month': [moment().startOf('month'), moment().endOf('month')],
		'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	  },
	  opens: 'left',
	  buttonClasses: ['btn btn-default'],
	  applyClass: 'btn-small btn-primary',
	  cancelClass: 'btn-small',
	  format: 'MM/DD/YYYY',
	  separator: ' to ',
	  locale: {
		applyLabel: 'Submit',
		cancelLabel: 'Clear',
		fromLabel: 'From',
		toLabel: 'To',
		customRangeLabel: 'Custom',
		daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
		monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		firstDay: 1
	  }
	};
	
	$('#reportrange span').html(moment().subtract(6, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
	$('#reportrange').daterangepicker(optionSet1, cb);
	$('#reportrange').on('show.daterangepicker', function() {
	  console.log("show event fired");
	});
	$('#reportrange').on('hide.daterangepicker', function() {
	  console.log("hide event fired");
	});
	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
	  console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
	});
	$('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
	  console.log("cancel event fired");
	});
	$('#options1').click(function() {
	  $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
	});
	$('#options2').click(function() {
	  $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
	});
	$('#destroy').click(function() {
	  $('#reportrange').data('daterangepicker').remove();
	});

}
</script>
*/?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.adminmain', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>