@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="">
            <div class="row top_tiles">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-user"></i></div>
                  <div class="count">{{$visits}}</div>
                  <h3>Visits</h3>
                  <p>No. of visits for last 7 days</p>
                </div>
              </div>
			  @if($order_enable)
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-bank"></i></div>
                  <div class="count">{{$orders}}</div>
                  <h3>Orders</h3>
                  <p>No. of orders for last 7 days</p>
                </div>
              </div>
			  @endif
			  @if($appointment_enable)
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-calendar-check-o"></i></div>
                  <div class="count">{{$appointments}}</div>
                  <h3>Appointment booking</h3>
                  <p>No. of appointment booking for last 7 days</p>
                </div>
              </div>
			  @endif
			  @if($table_booking_enable)
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-glass"></i></div>
                  <div class="count">{{$table_bookings}}</div>
                  <h3>Table booking</h3>
                  <p>No. of table booking for last 7 days</p>
                </div>
              </div>
			  @endif
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
                        <div class="col-md-5 tile">
                          <span>Total Orders</span>
                          <h2>{{$total_no_of_orders}}</h2>
                        </div>
                        <div class="col-md-7 tile">
                          <span>Total Revenue</span>
                          <h2>{{$currency->code.' '.$total_revenue}}</h2>
                          <span class="sparkline_sales graph" style="height: 160px;">
                                <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                          </span>
                        </div>
                        
                      </div>

                    </div>

                    <div class="col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <div class="x_title">
                          <h2>Recent Orders</h2>
                          <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                          </ul>
                          <div class="clearfix"></div>
                        </div>
						@if($recent_orders)
                        <ul class="list-unstyled top_profiles scroll-view">
							@foreach($recent_orders as $order)
							<li class="media event">
								<a class="pull-left border-aero profile_thumb">
									<img src="{{url('uploads/listing/thumbnail').'/'.$order->listing->image}}" style="width:25px;" />
								</a>
								<div class="media-body">
									@php
										$category = $order->listing->subcategory;
										$category_type = $category->category_type;//print_r($category);
										$url = '#';
										if($category_type) {
											$category_type_arr = json_decode($category_type);
											if(in_array(1, $category_type_arr)) // online order
												$url = url('merchant/online-order/booking-customers') . '/'. $order->id;
											else if(in_array(2, $category_type_arr)) // online shopping
												$url = url('merchant/customer-orders') . '/'. $order->id;
										}
									@endphp
								  <a class="title" href="{{$url}}">{{$order->listing->title}}</a>
								  <p>Total Amount:<strong> {{$currency->symbol . $order->total_amount}} </strong>({{$order->total_items}} Items)</p>
								  <p> <small>{{$order->created_at->diffForHumans()}}</small>
								  </p>
								</div>
							</li>
							@endforeach
                        </ul>
						@endif
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
                    <h2>Traffic <small>For my lisiting</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row" style="border-bottom: 1px solid #E0E0E0; padding-bottom: 5px; margin-bottom: 5px;">

                      <div class="col-md-5">
                        <div class="row" style="text-align: center;">
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
		var host="{{ url('merchant/get_order_graph') }}";
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
@stop