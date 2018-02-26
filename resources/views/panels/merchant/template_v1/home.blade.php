@extends('layouts.'.$merchant_layout)

@section('head')

<style type="text/css">
.columns {
    float: left;
    width:100%;
   /* padding: 8px;*/
}

.price {
    list-style-type: none;
    border: 1px solid #fff;
    margin: 0;
    padding: 0;
    -webkit-transition: 0.3s;
    transition: 0.3s;
}

.price:hover {
    box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
}

.price .header {
    background-color: #111;
    color: white;
    font-size: 25px;
}

.price li {
    border-bottom: 1px solid #fff;
    padding: 20px;
    text-align: center;
}

.price .grey {
    background-color: #fff;
    font-size: 20px;
}

.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    font-size: 18px;
}
.text-size{
	    font-size: 26px;
    font-weight: 900
}
.orangeText{
 color: #f58220;
}
.blue{
	background-color: #337ab7;
}
.whiteText{
	color: white;
}
@media only screen and (max-width: 600px) {
    .columns {
        width: 100%;
    }
}
 .dash-content{
 	    padding: 12px;
    /* padding-left: 97px; */
    position: relative
 }
.radial-content{
	    padding: 9px;
    text-align: -webkit-center;
    margin-left: 0%;
}

		 .ltr {
            direction: ltr;
             
            -moz-margin-start :8px;
            -webkit-margin-start :8px;
        }
        .header-title{
        	    padding: 36px;
        }
        .header-title span:nth-child(1){
    font-size: 32px;
        }
         .header-title span:nth-child(2){
     font-size: 20px;
    font-weight: 500;
        }
        .header-title span:nth-child(3){
  font-size: 13px;;
        }


.review-block{
    background-color: #ffffff;
    border: 1px solid #ffffff;
   /* padding: 15px; */
    border-radius: 3px;
   /* margin-bottom: 15px;*/
}
.review-block-name{
	    font-size: 15px;
    margin: 11px 0;
    margin-left: 7px
}
.review-block-date{
	font-size:12px;
}
.review-block-rate{
	font-size:13px;
	margin-bottom:15px;
}
.review-block-title{
	font-size:15px;
	font-weight:700;
	margin-bottom:10px;
}
.review-block-description{
	font-size:13px;
}
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

	<div class="row">
		<div class="col-md-12 dash-content">
			<div class="col-md-6 col-sm-6 col-xs-12 radial-content">
				<div class=" ">
					<div id="indicatorContainer" style="line-height:100px">
					 </div>
					   <div><p>Visitors  For last 7 Days</p></div>
			         </div>
		          </div>
		               <div class="col-md-6 col-sm-5 col-xs-12">
					    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pull-right ">
				            <div class="columns">
                                  <ul class="price">
                                    <li class="orangeText text-size">{{$currency->code.' '.$total_amount_week}}/- </li>
									<li class="blue text-size whiteText cursor">Amount</li>
									<li>for 7 Days</li>
									<li class="orangeText text-size">{{ $reviews }}</li>
									<li class="blue text-size whiteText cursor" data-toggle="modal" data-target="#exampleModal">Reviews	</li>
									<li class=" ">For 7 Days</li>
								</ul>
							</div>
					   </div>
					  </div>				  
					 </div>
				<hr>
			</div>
	
          
			
			<div class="row">
	           <div class="row">
		            <div class="col-md-12 header-title">
	                     <span><i class="fa fa-id-card-o" aria-hidden="true"></i></span><span class="ltr">Transaction Summary</span><span class="ltr">Weekly Report</span>
		            </div>
	           </div>
	        <div class="row">
		      <div class="col-md-12">
			<!-- <div id="chartContainer" style="height: 300px; width:100%;"></div> -->
			    <div class="filter">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                      </div>
                    </div>
					<div class="clearfix"></div>
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
										$url = '#';
										$category_type = '';
										if( isset($order->listing->subcategory) ) {
											$category = $order->listing->subcategory;
											$category_type = $category->category_type;//print_r($category);
										}
										if($category_type) {
											$category_type_arr = json_decode($category_type);
											if(in_array(1, $category_type_arr)) // online order
												$url = url('merchant/online-order/booking-customers') . '/'. $order->id;
											else if(in_array(2, $category_type_arr)) // online shopping
												$url = url('merchant/customer-orders') . '/'. $order->id;
										}
									@endphp
								  <a class="title" href="{{$url}}">{{$order->main_order->name}} ({{$order->main_order->phone_number}})</a>
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
<hr>
			
			

   <div class="row">
	<div class="row">
		<div class="col-md-12 header-title">
	         <span><i class="fa fa-id-card-o" aria-hidden="true"></i></span>
			 <span class="ltr">Traffic</span><span class="ltr">For my listing</span>
			 <!--<ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul> -->
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<!-- <div id="chartContainer" style="height: 300px; width:100%;"></div> -->
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <div class="col-md-4"> <h5 class="modal-title" id="exampleModalLabel">Reviews</h5></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	<div class="row">
		<div class="col-sm-12 col-md-12">
		    @if(!$listing_reviews)
			@foreach($listing_reviews as $review)
			  <div class="review-block">
				<div class="row">
					<div class="col-sm-3">
					<img src="{{url('')}}/user-template_v1/images/user-icon.png"
							class="img-rounded" style="width:60px;height:60px;">
							<div class="review-block-name"><a href="#" class=" btn btn-primary" style="background-color: orange">{{ $review->rating }}</a></div>
							<!-- <div class="review-block-date">January 29, 2016<br/>1 day ago</div> -->
						</div>
						<div class="col-sm-9">
							<div class="review-block-rate">
								 <label>Posted by : {{ $review->name }}</label> - {{ $review->created_at->formatLocalized('%A %d %B %Y') }} 
							</div>
							<div class="review-block-title">{{ $review->title }}</div>
							<div class="review-block-description">{{ $review->comments }}</div> 
						</div>
					</div>
					<hr/>
					@endforeach
					@else
					<p style="text-align:center;">No Reviews Found Here !!!</p>
					@endif
					
				</div>
			</div>
		</div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
  

@stop

@section('footer')
<script src="{{ asset('admin-assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- Chart.js -->
<script src="{{ asset('admin-assets/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('admin-assets/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('admin-assets/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/js/rangeslider.js') }}"></script>
  <script src="{{ asset('assets/js/rangeslider.min.js') }}"></script>
  <script src="{{ asset($merchant_layout . '/js/radialIndicator.js') }}"></script>
  <script src="{{ asset($merchant_layout . '/js/custom.min.js') }}"></script>
 
  
<script>
$(document).ready(function() {
	
	var test2 = <?php echo $visits; ?>

	$('#indicatorContainer').radialIndicator({
        barColor: '#0066b3',
        barWidth: 10,
        initValue: test2,
        roundCorner : true,
        radius:85,
        maxValue: 1000
});
	
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
@stop