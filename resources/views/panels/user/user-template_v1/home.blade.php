@extends('layouts.'.$user_layout)

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset($user_layout . '/css/outsorce.css') }}">
@stop

@section('content')

<style type="text/css">
	
	.order-items-list{
  line-height: 25px;
    font-size: 16px;
    font-weight: 600;
    color: #f58634;
}
 .ltr {
            direction: ltr;
             
            -moz-margin-start :64px;
            -webkit-margin-start :64px;
        }
        .delivered-text{
        	    font-size: 15px;
    font-weight: 600;
    color: #736f6f;
    line-height: 22px;
    margin-bottom: 10px;
        }
        .delivered-text .ltr {
 
            -webkit-margin-start :80px;
        }
        .order-total-text{
font-weight: 900;
  
        }
         .order-total-text p{
     font-size: 19px;
        }
</style>
<div class="main-content">
	<section class="profile__img_block">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="profile__img">
						<div class="card hovercard">
							<div class="avatar">
										<?php if(Auth::user()->image){?>				
										<img alt="" src="{{ url('uploads/thumbnail') }}/{{ Auth::user()->image }}" class="profile-pic">
										<?php }else{?>
										<img alt="" src="{{ asset($user_layout . '/images/user-icon.png') }}" class="profile-pic">
										<?php }?>
								<!-- plus icons start -->
								<!--<div class="profile_plus_icon">
									<a href="#">
										<i class="fa fa-plus" aria-hidden="true"></i>
									</a>
								</div>-->
								<!-- plus icons end -->
								<!-- profile info -->
									<div class="info">
										<div class="profile_title">
										<a target="_blank" href="#">{{ Auth::user()->first_name }}</a>
										</div>
										<div class="desc"><?php if(Auth::user()->bio){?> <p>{{ Auth::user()->bio }}</p><?php }?></div>
									<div class="desc edit_profile">
										<a href="{{url('user/profile')}}">Edit Profile <span>
										<i class="fa fa-pencil" aria-hidden="true"></i>
										</span></a>
									</div>
									</div>
								<!-- profile info end -->
							</div>
						</div>
						<div class="profile_img_view">
							<img src="{{ asset($user_layout . '/images/gift.png') }}" class="img_center" />
						</div>
						<!-- progress bar start -->
						<?php 
						$tot=0;
						$level_max_points = 1500;
						$total_points = Auth::user()->total_points;
						
						$val = $total_points / 1500;
						$tot=$val*100;
						?>
						<div class="profile_progress_bar_start col-sm-5">
							<div class="progress">
								<div data-percentage="0%" style="width: <?php echo $tot;?>%;" class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<ul class="profile_points">
								<li><?php echo Auth::user()->total_points?>
											
								</li>
								<li>
									Reward Points
								</li>
								<li><?php echo $level_max_points;?></li>
							</ul>
						</div>
						<!-- progress bar end -->
					</div>
				</div>
				<div class="col-sm-12">
					<div class="box_count_block">

						<!--<ul class="three_box_counts container">
							<li>
								<div class="box_one">
									<div class="profile__points_counts">
										<p>20</p>
										<p>BEEN THERE</p>
									</div>
								</div>
							</li>
							<li>
								<div class="box_one">
									<div class="profile__points_counts">
										<p>20</p>
										<p>BEEN THERE</p>
									</div>
								</div>
							</li>
							<li>
								<div class="box_one">
									<div class="profile__points_counts">
										<p>20</p>
										<p>BEEN THERE</p>
									</div>
								</div>
							</li>
						</ul>-->
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="clearfix"></div>
	<!-- profile  end -->
	<!-- tabs start -->
	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					
					@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
					
					<div class="tabbable-panel">
						<div class="tabbable-line">
							<ul class="nav nav-tabs ">
								<li class="active">
									<a href="#order_history" data-toggle="tab">
										<span class="d_block"><i class="fa fa-pencil" aria-hidden="true"></i></span> Ordered History </a>
								</li>
								<li>
									<a href="#reviews" data-toggle="tab">
										<span class="d_block">
							<i class="fa fa-list" aria-hidden="true"></i>
							</span> Reviews </a>
								</li>
								<!--<li>
									<a href="#campaign" data-toggle="tab">
										<span class="d_block"><i class="fa fa-bullhorn" aria-hidden="true"></i></span> Campaign
									</a>
								</li>-->
								<li>
									<a href="#saved" data-toggle="tab">
										<span class="d_block"><i class="fa fa-download" aria-hidden="true"></i></span> Saved
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="order_history">
									<div class="sub_nav_tab">
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#all" data-toggle="tab">
							Appointment Booking </a>
											</li>
											<li>
												<a href="#tablebooking" data-toggle="tab">								
							Table Booking </a>
											</li>
											<li>
												<a href="#food" data-toggle="tab">								
							Food </a>
											</li>
											<li>
												<a href="#products" data-toggle="tab">
							Products</a>
											</li>
											<!--<li>
												<a href="#services" data-toggle="tab">
							Services</a>
											</li>-->
										</ul>
										<!-- sub tab -->
										<div class="tab-content">
											<div class="tab-pane active" id="all">
											<br />
											<h3>Appointment Booking</h3>
											@if($appointment_bookings)
											@foreach($appointment_bookings as $appointment_booking)
												@if(isset($appointment_booking->listing) and $appointment_booking->listing->count() > 0)
												<!-- track block start -->
												<div class="track_block">
													<div class="pull-left">
														<p class="order_history_track_number">{{ isset($appointment_booking->main_order->invoice_id) ? $appointment_booking->main_order->invoice_id : $appointment_booking->id}}</p>
													</div>
													<!-- <div class="pull-right">
														<p class="order_history_track">
															<a href="">
																<span><i class="fa fa-map-marker" aria-hidden="true"></i></span> Track
															</a>
														</p>
													</div>-->
												</div>
												<!-- track block end -->
												
												
												<div class="clearfix"></div>
												<div class="col-sm-3 col-md-3">
												@if(isset($appointment_booking->listing->image) and $appointment_booking->listing->image)
													<img src="{{url('uploads/listing/thumbnail')}}/{{$appointment_booking->listing->image}}" class="img-responsive">
												@else
													<img src="{{ asset($merchant_layout . '/images/logo-icon-l.png')}}" class="img-responsive">
												@endif
												</div>
												<div class="col-sm-9 col-md-9">
													<!-- left side order title start -->
													<div class="order_his_title pull-left">
														<h3>{{@$appointment_booking->listing->title}}</h3>
														<p><span class="order_date">Booked on</span>
															<span><?php echo date('D, M, Y', strtotime($appointment_booking->order_date))?> </span></p>
														<p>Appointment on <?php echo date('D, M, Y', strtotime($appointment_booking->booked_date))?></p>
														<p class="order_status">
															<?php 
															if($appointment_booking->status==0){
																echo 'Pending';
															} else if($appointment_booking->status==1){
																echo 'Confirmed';
															} else if($appointment_booking->status==2){
																echo 'Cancelled';
															} else{
																echo 'Completed';
															}
															?>
														</p>
													</div>
													<!-- left side order title end -->
													<!-- right side order title start
													<div class="pull-right">
														<h3><i class="fa fa-inr" aria-hidden="true"></i> 160</h3>
													</div> -->
													<!-- right side order title end -->
													<div class="clearfix"></div>
													<!-- total order start -->
													<div class="text-center">
														<p>Booked Time: <?php echo date('g: h: A', strtotime($appointment_booking->booked_time))?></p>
													</div>
													<!-- total order end -->
												</div>
												@endif
											@endforeach
											@endif
											</div>
											<!-- food -->
											<div class="tab-pane" id="products">
											<br />
											<h3>Products</h3>
										
											@if(isset($orderbooking))
											@foreach($orderbooking as $orderbooking)	
											
											
											<!-- track block start -->
												<div class="track_block">
													<div class="pull-left">
														<p class="order_history_track_number">{{ @$orderbooking->main_order->invoice_id  }}</p>
													</div>
													<!-- <div class="pull-right">
														<p class="order_history_track">
															<a href="">
																<span><i class="fa fa-map-marker" aria-hidden="true"></i></span> Track
															</a>
														</p>
													</div>-->
												</div>
												<!-- track block end -->
												
												
												@foreach($orderbooking->merchant_orders as $merchant_orders)
												
															@foreach($merchant_orders->order_details as $order_details)
															<?php //print_r($order_details->shop_item->pro_name);exit;?>
															<div class="clearfix"></div>
																<div class="col-sm-3 col-md-3">								
																@if(isset($order_details->shop_item->pro_img) and $order_details->shop_item->pro_img)
																	<img src="{{url('')}}/upload/images/small/{{$order_details->shop_item->pro_img}}" class="img-responsive">		
																@else
																	<img src="{{ asset($merchant_layout . '/images/logo-icon-l.png')}}" class="img-responsive">
																@endif
															
																</div>
															<div class="col-sm-9 col-md-9">
															<!-- left side order title start -->
																<div class="order_his_title pull-left">
																	<h3>{{@$order_details->shop_item->pro_name}}</h3>
																	<p><span class="order_date">Qty:{{$order_details->quantity}}</span></p>
																	<p><span class="order_date">Ordered on</span>
																	<span><?php echo date('D, M, Y', strtotime($order_details->created_at))?></span></p>
																	
																	<?php if($order_details->delivery_date and $order_details->status != 'cancelled'):?>
																		<?php
																			$delivered_on_txt = 'Delivered on';
																			if($order_details->status == 'progressed' || $order_details->status == 'shipping')
																				$delivered_on_txt = 'Will be delivered on';
																		?>
																		<p>{{$delivered_on_txt}} <?php echo date('D, M, Y', strtotime($order_details->delivery_date))?></p>
																	<?php endif;?>
																	
																	<p class="order_status">{{$orderbooking->order_detail_status[$order_details->status]['name']}}</p>
																</div>
															<!-- left side order title end -->
															<!-- right side order title start -->
															<!-- <div class="pull-right">
															<h3><i class="fa fa-inr" aria-hidden="true"></i> 160</h3>
															</div> -->
															<!-- right side order title end -->
															<div class="clearfix"></div>
															<!-- total order start -->
															<div class="text-center order-total-text">
																<p>Price: <?php echo $currency->symbol;?> {{$order_details->total_amount}}</p>
															</div>
															<!-- total order end -->
															</div>
																<hr>
															@endforeach 
													@endforeach 
											
											@endforeach 
											@endif
											</div>
											
											
											
											
											<!-- Food Booking -->
											<div class="tab-pane" id="food">
												<br />
												<h3>Food</h3>
												@if($foodbooking)
													<!-- track block start -->
													@foreach($foodbooking as $foodbooking)	
													@if($foodbooking->merchant_orders and $foodbooking->main_order)
														@foreach($foodbooking->merchant_orders as $merchant_orders)
														<div class="track_block">
															<div class="pull-left">
																<p class="order_history_track_number">{{ @$foodbooking->main_order->invoice_id  }}</p>
																</div>
																<!--<div class="pull-right">
																<p class="order_history_track"><a href="">
																<span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
																Track</a></p>
															</div>-->
														</div>
													
														<!-- track block end -->
														<div class="clearfix"></div>
														<div class="col-sm-3 col-md-3">
															@if(isset($merchant_orders->listing->image) and $merchant_orders->listing->image)
														<img src="{{url('uploads/listing/thumbnail')}}/{{$merchant_orders->listing->image}}" class="img-responsive">
														@else
														<img src="{{ asset($merchant_layout . '/images/logo-icon-l.png')}}" class="img-responsive">
														@endif
														</div>
														<div class="col-sm-9 col-md-9">
														<!-- left side order title start -->
														<div class="order_his_title pull-left col-sm-12 col-md-12">
															<h3>{{@$merchant_orders->listing->title}}</h3>
															<div class="order-items-list  ">
																	@foreach($merchant_orders->order_details as $order_detail)																	
																			
																		<div >
																		<span class="lft col-sm-6 col-md-6">{{@$order_detail->menu_item->name}}</span><span class="col-sm-3 col-md-3"> {{$order_detail->quantity}} </span><span class="col-sm-3 col-md-3"><?php echo $currency->symbol;?> {{$order_detail->total_amount}}
																		</span>
																		</div>														
																	@endforeach 	
															</div>
															
															<div class="delivered-text"> 
																<span class="">(Delivery Charges)</span><span class="ltr"> </span><span class="ltr"> </span><span class="ltr"><?php echo $currency->symbol;?> {{$merchant_orders->total_shipping_amount}}
																</span>
															</div>
															<p><span class="order_date">Ordered on</span>
															<span><?php echo date('D, M, Y', strtotime($foodbooking->created_at))?></span></p>
															<?php if($merchant_orders->delevery_date and $order_detail->status != 'cancelled'):?>
																<?php
																	$delivered_on_txt = 'Delivered on';
																	if($order_detail->status == 'progressed' || $order_detail->status == 'confirmed')
																		$delivered_on_txt = 'Will be delivered on';
																?>
																<p>{{$delivered_on_txt}} <?php echo date('D, M, Y', strtotime($merchant_orders->delevery_date))?></p>
															<?php endif;?>
															<p class="order_status">{{$foodbooking->merchant_order_status[$order_detail->status]['name']}}</p>
														</div>
														<!-- left side order title end -->
														<!-- right side order title start -->
														<!-- <div class="pull-right">
														<h3><i class="fa fa-inr" aria-hidden="true"></i> 160</h3>
														</div> -->
														<!-- right side order title end -->
														<div class="clearfix"></div>
														<!-- total order start -->
														<div class="text-center order-total-text">
														<p>Order Total: <?php echo $currency->symbol;?> {{$merchant_orders->total_amount}}</p>
														</div>
														<!-- total order end -->
														</div>

														<hr>
														@endforeach 
													@endif
													@endforeach 
												@endif
											</div>
											
						<!--Food Booking End-->					
											
											<!-- Table Booking Start-->
											<div class="tab-pane" id="tablebooking">
											<br />
												<h3>Table Booking</h3>
												@foreach($tablebooking as $tablebooking)
												<!-- track block start -->
												<div class="track_block">
													<div class="pull-left">
														<p class="order_history_track_number">{{@$tablebooking->main_order->invoice_id}}</p>
													</div>
													<!-- <div class="pull-right">
														<p class="order_history_track">
															<a href="">
																<span><i class="fa fa-map-marker" aria-hidden="true"></i></span> Track
															</a>
														</p>
													</div>-->
												</div>
												<!-- track block end -->
												
												
												<div class="clearfix"></div>
												<div class="col-sm-3 col-md-3">
												@if(isset($tablebooking->listing->image) and $tablebooking->listing->image)
													<img src="{{url('uploads/listing/thumbnail')}}/{{$tablebooking->listing->image}}" class="img-responsive">
												@else
													<img src="{{ asset($merchant_layout . '/images/logo-icon-l.png')}}" class="img-responsive">
												@endif
												</div>
												<div class="col-sm-9 col-md-9">
													<!-- left side order title start -->
													<div class="order_his_title pull-left">
														<h3>{{@$tablebooking->listing->title}}</h3>
														<p><span class="order_date">Booked on</span>
															<span><?php echo date('D, M, Y', strtotime($tablebooking->order_date))?> </span></p>
														<p>Booked Date: <?php echo date('D, M, Y', strtotime($tablebooking->booked_date))?></p>
														<p class="order_status"><?php if($tablebooking->status==0){echo 'Pending';}elseif($tablebooking->status==1){echo 'Confirmed';}elseif($tablebooking->status==2){echo 'Cancelled';}else{echo 'Completed';}?></p>
													</div>
													<!-- left side order title end -->
													<!-- right side order title start
													<div class="pull-right">
														<h3><i class="fa fa-inr" aria-hidden="true"></i> 160</h3>
													</div> -->
													<!-- right side order title end -->
													<div class="clearfix"></div>
													<!-- total order start -->
													<div class="text-center">
														<p>Booked Time: <?php echo date('g: h: A', strtotime($tablebooking->booked_time))?></p>
													</div>
													<!-- total order end -->
												</div>
												<hr>
												@endforeach
											</div>
											<!-- Table Booking End-->
											<!-- services -->
											<div class="tab-pane" id="services">
												<p>Services</p>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane" id="reviews">
								@foreach($review as $reviews)
								
									<!-- review one strat -->
									<div class="well well_custom">
										<div class="row border_block">
											<div class="col-sm-1 review_img"> 
											@if(isset($reviews->listing->image) and $reviews->listing->image)
											<img src="{{url('uploads/listing/thumbnail')}}/{{$reviews->listing->image}}">
										@else
											<img src="{{ asset($merchant_layout . '/images/logo-icon.png')}}">
											@endif
											</div>
											<div class="col-sm-9 review_info">
												<h3>{{ @$reviews->listing->title }}</h3>
												<span>{{ @$reviews->listing->address1 }}, {{ @$reviews->listing->listing_city->name }}</span>
											</div>

										</div>
										<div class="row">
											<div class="pull-left">
												<div class="review-block-rate">
												 <style>.srate{padding: 2px;font-size: 19px;color: #ff7200;}</style>	
									@for ($i = 0; $i < 5; $i++)
									@if($i < $reviews->rating)
									<i class="fa fa-star srate"></i>
									@else
									<i class="fa fa-star-o srate"></i>
									@endif   	
									@endfor
												
													
													<div class="btn-rating-count-green">
														<span>{{ $reviews->rating }}</span>
													</div>
												</div>
											</div>
											<div class="pull-right">
												<p class="review_month"><?php echo date('D, M, Y', strtotime($reviews->created_at))?></p>
											</div>
											<div class="clearfix"></div>
											<div class="review_desc">
												<p>"{{ $reviews->comments }}"</p>
											</div>
										</div>
									</div>
									<hr>
									@endforeach
									
									<!-- review one end -->
									
									<!-- review two end -->
								</div>
								<?php /*
								<div class="tab-pane" id="campaign">
								<div class="well well_custom">
									<p>
										Howdy, I'm in Tab 3.
									</p>
									<p>
										Duis autem vel eum iriure dolor in hendrerit in vulputate. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat
									</p>
									<p>
										<a class="btn btn-info" href="#" target="_blank">
									Learn more...
								</a>
									</p>
									</div>
								</div>
								*/?>
								<div class="tab-pane" id="saved">
							<div class="well well_custom">		
					@foreach($contacts as $contact)
					@if(isset($contact->listing) and $contact->listing->count() > 0)
					<div class="col-sm-4 well">
						<a href="{{url('')}}/user/removecontact/{{$contact->listing->id}}"><i class="fa fa-window-close" aria-hidden="true" style="float: right;"></i></a>
						<address>
							<h3 style="margin-bottom: 8px;"><a href="{{ url('') }}/{{$contact->listing->slug}}">{{$contact->listing->title}}</a></h3>
							Visit at: <b>{{$contact->listing->website}}</b><br/>
							{{$contact->listing->address1}}<br/>
							pincode: {{$contact->listing->pincode}}<br/>
							Contact at: {{$contact->listing->phoneno}}
						</address>
					</div>
					<div class="col-sm-1">
					</div>
					@endif
					@endforeach
					</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- tabs end -->

</div>
@stop

@section('footer')
<script>
	$(document).ready(function(){
		
		if(window.location.hash) {
			var hash = window.location.hash.substring(1);
			activaTab(hash);
		}
		function activaTab(tab){
			$('.nav-tabs a[href="#' + tab + '"]').tab('show');
			$('html, body').animate({
				scrollTop: $(".avatar").offset().top
			}, 100);
		};
		
		$('.user_menu a').click(function(){
			var hash = $(this).data('hash');
			if(hash && $('.nav-tabs a[href="#' + hash + '"]').length > 0)
			{
				activaTab(hash);
			}
		});
		
	});
</script>
@stop