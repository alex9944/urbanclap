@extends('layouts.merchantmain')

@section('content')

@include('partials.status-panel')

	<style>
	ul.shop-progress li{
		margin-right:0px; !important
	}
</style>
	
	<!-- support start -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row">
				<section>
					<div class="col-md-10 col-md-offset-1">
						<div class="wizard">
							<div class="wizard-inner">

								<ul class="shop-progress nav nav-tabs" role="tablist">
									<li class="active">
										<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
											<span class="thumb-icon">1</span>
											<span class="title">Category </span>
										</a>
									</li>
									<li>
										<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
											<span class="thumb-icon">2</span>
											<span class="title">Profile</span>
										</a>
									</li>
									<li>
										<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
											<span class="thumb-icon">3</span>
											<span class="title">Subscription</span>
										</a>
									</li>
									<li>
										<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4">
											<span class="thumb-icon">4</span>
											<span class="title">Payment</span>
										</a>
									</li>
								</ul>
								
							</div>

							<form name="subscriptionForm" action="{{url('merchant/complete-subscription-process')}}" id="subscriptionForm" method="post" role="form">
								<div class="tab-content">
							
									@if ($errors->any())
										<div class="alert alert-danger">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif
									
									<?php
										$category_id = old('category_id', $subscribed_category->c_id);	
										$subscription_pricing_id = old('subscription_pricing_id', $subscription_pricing->id);
										$unit_price = old('unit_price', $last_subscription->unit_price);
										$selected_category = '';
										$selected_plan = '';	
										$selected_cat_types = '';
									?>
									
									<div class="tab-pane active" role="tabpanel" id="step1">
										
										@foreach($categories as $category)
											<?php
											$category_type_arr = array();
											$cat_types = '';											
											$sep = '';
											$subs_cat_types = '';
											if($category->category_type)
												$category_type_arr = json_decode($category->category_type);
											if(!empty($category_type_arr)) 
											{
												$subs_cat_types = '<table class="table">';
												foreach($category_types as $cat_type)
												{
													if(in_array($cat_type->id, $category_type_arr))
													{
														$cat_types .= $sep . $cat_type->name;
														$sep = '|';
														
														$subs_cat_types .= '<tr><td>'.$cat_type->name.'</td></tr>';
													}
												}
												$subs_cat_types .= '</table>';
											}
											
											$category_box_style = ' style="border: 1px solid rgba(128, 128, 128, 0.17); margin:20px;"';
											if($category_id == $category->c_id) {
												$category_box_style = ' style="border: #337ab7 1px solid; margin:20px;"';
												$selected_category = $category->c_title;
												$selected_cat_types = $subs_cat_types;
											}
											?>
										  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-20">
										  <div class="category_box" id="category_box_{{ $category->c_id }}"<?php echo $category_box_style;?>>
											<a class="select_category" href="javascript:;" data-id="{{ $category->c_id }}" data-name="{{ $category->c_title }}" data-types="{{ $cat_types }}">
												<span class="width-75">
													<img alt="" class="width-60 icon-gray" src="{{ url('/uploads/thumbnail') . '/' . $category->c_image }}">
												</span>
												<span class="icon-text">{{ $category->c_title }}</span>
											</a>
										  </div>
										  </div>
										@endforeach	
										
										<div style="clear:both;">
													
											<div class="next_1_validation alert alert-danger hidden"></div>

											<ul class="list-inline pull-right">
												<li>
													<button type="button" class="btn btn-default next_1">Next</button>
												</li>
											</ul>
										</div>
										
										<input type="hidden" name="category_id" id="category_id" value="{{$category_id}}">
									</div>
									<div class="tab-pane" role="tabpanel" id="step2">
										<div class="col-md-12 ">

											<div class="reg-form">
												<div class="login-form" style="border:0px;">
												
													<div class="form-group mt20">
														<input type="text" name="first_name" id="first_name" placeholder="First name" class="form-control" value="{{old('first_name', $merchant_user->first_name)}}">
													</div>
													<div class="form-group">
														<input type="text" name="last_name" id="last_name" placeholder="Last name" class="form-control" value="{{old('last_name', $merchant_user->last_name)}}">
													</div>
													<div class="form-group">
														<input type="text" name="email" id="email" placeholder="Email address" class="form-control" value="{{old('email', $merchant_user->email)}}">
													</div>
													<div class="form-group">
														<input type="text" name="mobile" id="mobile" placeholder="Mobile No" class="form-control" value="{{old('mobile', $merchant_user->mobile_no)}}">
													</div>
													<div class="form-group">
														<input type="text" name="address" id="address" placeholder="Address" class="form-control" value="{{old('address', $merchant_user->address)}}">
													</div>
													<div class="form-group">
														<input type="text" name="city_name" id="city_name" placeholder="City name" class="form-control" value="{{old('city_name', $merchant_user->city_name)}}">
													</div>
													<div class="form-group">
														<input type="text" name="postal_code" id="postal_code" placeholder="Postal code" class="form-control" value="{{old('postal_code', $merchant_user->postal_code)}}">
													</div>
													
													<div style="clear:both;">

													<ul class="list-inline pull-right">
														<li>
															<button type="button" class="btn btn-default next_2">Next</button>
														</li>
													</ul>
													</div>
													
												</div>

											</div>

										</div>

									</div>

									<div class="tab-pane" role="tabpanel" id="step3">
										<div class="col-md-12 ">
											<div class="row">
												@foreach($subscription_pricings as $subscription_pricing)
												<div class="col-xs-12 col-md-4">
													<?php
													$subscription_box_style = '';
													$plan_info = $subscription_pricing->plan->title . ' - ' . 
																$currency->symbol . $subscription_pricing->price .
																' for ' . $subscription_pricing->duration->title;
													if($subscription_pricing->duration->days_month=='1')
														$plan_info .= ' Days';
													else
														$plan_info .= ' Month';
																
													if($subscription_pricing_id == $subscription_pricing->id) {
														$subscription_box_style = ' style="border: #337ab7 3px solid"';
														$selected_plan = $plan_info;
													}
													?>
													<div class="panel panel-primary subscription_box" id="box_{{$subscription_pricing->id}}"<?php echo $subscription_box_style;?>>
														<div class="panel-heading">
															<h3 class="panel-title">{{$subscription_pricing->plan->title}}</h3>
														</div>
														<div class="panel-body">
															<div class="the-price">
																<h1>
																{{$currency->symbol}}{{$subscription_pricing->price}}
																<span class="subscript"> <small>for {{$subscription_pricing->duration->title}}
																@if($subscription_pricing->duration->days_month=='1') Days @else Month @endif</small></span>
																</h1>
															</div>
															<table class="table">
																<?php
																$f_id = $subscription_pricing->f_id;
																$features = explode(',', $f_id);
																$active = 'active';
																foreach($subscription_features as $subscription_feature) {
																	if(in_array($subscription_feature->id, $features)) {
																			$hidden = '';
																																						
																			if($subscription_feature->functionality_name == 'category_dependent') {
																				$hidden = ' hidden';
																			}
																			if(!empty($selected_cat_types)) {
																				$hidden = '';
																			}
																?>	
																	<tr class="{{$active.$hidden}}" id="plan_{{$subscription_feature->functionality_name}}">
																		<td>
																			<?php 
																			if(!empty($selected_cat_types) and $subscription_feature->functionality_name == 'category_dependent') {
																				echo $selected_cat_types;
																			} else {
																				echo $subscription_feature->title;
																			}
																			?>
																		</td>
																	</tr>
																<?php
																		if($active == '')
																			$active = 'active';
																		else
																			$active = '';
																	}
																}
																?>
															</table>
														</div>
														<div class="panel-footer">
															<a href="javascript:;" data-id="{{$subscription_pricing->id}}" data-unit_price="{{$subscription_pricing->price}}" class="btn btn-default select_subscription" role="button" data-plan_info="{{$plan_info}}">Select</a></div>
													</div>
												</div>
												@endforeach
												
												<div style="clear:both;">
													
													<div class="next_3_validation alert alert-danger hidden"></div>

													<ul class="list-inline pull-right">
														<li>
															<button type="button" class="btn btn-default next_3">Next</button>
														</li>
													</ul>
												</div>
												
												<input type="hidden" name="subscription_pricing_id" id="subscription_pricing_id" value="{{$subscription_pricing_id}}">
												<input type="hidden" name="unit_price" id="unit_price" value="{{$unit_price}}">
												

											</div>
										</div>
										<!--  <ul class="list-inline pull-right">
									<li><button type="button" class="btn btn-default prev-step">Previous</button></li>
									<li><button type="button" class="btn btn-default next-step">Skip</button></li>
									<li><button type="button" class="btn btn-default btn-info-full next-step">Save and continue</button></li>
								</ul> -->
									</div>
									<div class="tab-pane" role="tabpanel" id="step4">

										<p>Subscribed Category: <span id="selected_category">{{$selected_category}}</span></p>
										<p>Subscribed Plan: <span id="selected_plan">{{$selected_plan}}</span></p>
										
										<h6 class="heading2">Payment Method</h6>
										<div style="margin:0 0 30px 0">
										@foreach($payment_gateways as $gateway)
											<input type="radio" name="payment_gateway_id" value="{{$gateway->id}}" > {{$gateway->name}} 
										@endforeach
										</div>
										
										<h6 class="heading2">Subscription Term</h6>
										<div style="margin:0 0 50px 0">
										@foreach($subscription_terms as $term)
											<input type="radio" name="subscription_term_id" data-type="{{$term->term_type}}" data-value="{{$term->term_value}}" value="{{$term->id}}" class="subscription_terms"@if($last_subscription->subscription_term_id == $term->id) checked @endif > {{$term->display_value}} 
										@endforeach
										</div>
										
										<h4>
											Payable Amount <?php echo $currency->symbol; ?>
											<span id="grand_total">{{$last_subscription->paid_amount}}</span>
											<input type="hidden" name="paid_amount" id="paid_amount" value="{{$last_subscription->paid_amount}}">
										</h4>
										
										<button type="submit" name="subscribe_order" class="btn-default">Place Order Now</button>
									</div>
									<div class="clearfix"></div>
								</div>
								
								{{ csrf_field() }}
								
							</form>
						</div>
					</div>
				</section>
			</div>
		</div>
		
		
	</div>
	<!-- end support -->


<script>
$(document).ready(function(){
	$("body").delegate('.select_category', 'click', function(){
		var category_id = $(this).data('id');
		var category_types = $(this).data('types');
		var category_name = $(this).data('name');
		$("#category_id").val(category_id);
		
		hideAlertBox(".next_1_validation");
		
		$('.category_box').css('border', '#e4e3e2 1px solid');
		// highlight box
		$('#category_box_'+category_id).css('border', '#337ab7 1px solid');
		
		// update final tab
		$('#selected_category').html(category_name);
		if(category_types != '') {
			var category_types_arr = category_types.split('|');
			var cat_rows = '<table class="table">';
			$.each(category_types_arr, function(index, value) {
				cat_rows += '<tr><td>'+value+'</td></tr>';
			});
			cat_rows += '</table>';
			$('#plan_category_dependent').removeClass('hidden');
			$('#plan_category_dependent td').html(cat_rows);
		} else {
			$('#plan_category_dependent').addClass('hidden');
			$('#plan_category_dependent td').html('');
		}
	});
	
	$("body").delegate('.next_1', 'click', function(){
		
		hideAlertBox(".next_1_validation");
		
		var category_id = $("#category_id").val();
		if(category_id == '') {
			$(".next_1_validation").removeClass('hidden');
			$(".next_1_validation").html('Please select category');
			$(".next_1_validation").show();
		} else {
			$('.nav-tabs a[href="#step2"]').tab('show');
		}
	});
	
	function hideAlertBox(clsname) {
		$(clsname).addClass('hidden');
		$(clsname).html('');
		$(clsname).hide();
	}
	
		
	$("body").delegate('.next_2', 'click', function(){
		
		$('.nav-tabs a[href="#step3"]').tab('show');
	});
	
	$("body").delegate('.select_subscription', 'click', function(){
		var subscription_pricing_id = $(this).data('id');
		var unit_price = $(this).data('unit_price');
		var plan_info = $(this).data('plan_info');
		$("#subscription_pricing_id").val(subscription_pricing_id);
		$('#unit_price').val(unit_price);
		update_price();
		
		hideAlertBox(".next_3_validation");
		
		$('.subscription_box').css('border', '#e4e3e2 1px solid');
		// highlight box
		$('#box_'+subscription_pricing_id).css('border', '#337ab7 3px solid');
		
		// update final tab
		$('#selected_plan').html(plan_info);
	});
	
	$("body").delegate('.next_3', 'click', function(){
		
		hideAlertBox(".next_3_validation");
		
		var subscription_pricing_id = $("#subscription_pricing_id").val();
		if(subscription_pricing_id == '') {
			$(".next_3_validation").removeClass('hidden');
			$(".next_3_validation").html('Please select category');
			$(".next_3_validation").show();
		} else {
			$('.nav-tabs a[href="#step4"]').tab('show');
		}
	});
	
	function update_price() {
		var price = $('#unit_price').val();
		var term_type = $('input[name="subscription_term_id"]:checked').attr("data-type");//$(this).data('type');
		var term_value = $('input[name="subscription_term_id"]:checked').attr("data-value");//$(this).data('value');
		
		if(term_type != '')
		{
			if(term_type == 'month') {
				price = price*term_value;
			} else {
				price = price*term_value*12;
			}
			
			$('#grand_total').html(price);
		}
	}
	//update_price();
	
	$(document).on("change", ".subscription_terms", update_price);
	
});
</script>

@stop