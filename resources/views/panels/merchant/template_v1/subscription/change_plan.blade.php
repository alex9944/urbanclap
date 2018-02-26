@extends('layouts.'.$merchant_layout)

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/subcription_plan.css') }}">
@stop

@section('content')
	
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	
	@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
	
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6 col-md-offset-3" id="subscriptionTabs">
				<a class="btn btn-cus active planbtn" data-toggle="tab" href="#subplantogg">Plan</a>
				<a class="btn  pull-right btn-cus historybtn" data-toggle="tab" href="#historytogg">History</a>
			</div>
		</div>

	</div>
	@if($last_subscription->subscription_pricing_id != $plan_change_free_subscription_pricing_id)
	<br><br>
	<div class="alert alert-success" style="color: #f6f9f6; background-color: #161715; border-color: #161715;">
		<div class="pull-left">Current Plan Expiry Date <span style="color:#1395d1;"><?php echo date('F d, Y', strtotime($last_subscription->expired_date));?></span></div> 
		<div class="pull-right">
		Remaining Days: 
		<?php
		$expired_date_time = strtotime($last_subscription->expired_date);
		$today_time = time();
		$datediff = $expired_date_time - $today_time;
		?>
		<span style="color:#f14e32;">
		<?php
		echo ceil($datediff / (60 * 60 * 24));
		?>
		</span>
		</div>
		<br>
	</div>
	@endif
	
	<?php
		$subscription_pricing_id = $last_subscription->subscription_pricing_id;
		$lower_plan = true;
		$show_submit_button = false;
	?>
	<form name="subscriptionForm" action="" id="subscriptionForm" method="post" role="form">
	<div class="tab-content">
		<!-- *******************Subcription Plan **************** -->
		<div class="tab-pane active subplantogg" id="subplantogg">
			<div class="row">
				<div class="col-md-12 mt10 col-md-offset-1">
					<div class="col-md-7 col-sm-9 col-xs-12 subplan">
						@foreach($subscription_pricings as $subscription_pricing)
							<?php
							$subscription_box_class = '';
							$plan_info = $subscription_pricing->plan->title . ' - ' . 
										$currency->symbol . $subscription_pricing->price .
										' for ' . $subscription_pricing->duration->title;
							if($subscription_pricing->duration->days_month == '1')
								$plan_info .= ' Days';
							else
								$plan_info .= ' Month';
										
							if($last_subscription->subscription_pricing_id == $subscription_pricing->id) {
								$subscription_box_class = ' active';
							}
							
							?>
							<div class="divbox">
								<a href="javascript:;" data-id="{{$subscription_pricing->id}}" data-unit_price="{{$subscription_pricing->price}}" data-plan_info="{{$plan_info}}" class="select_subscription<?php echo $subscription_box_class;?>">

									<span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
									<div class="  wrapperrec">
										<div class="col-md-12"><i class="fa fa-info-circle pull-right info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{$subscription_pricing->description}}"></i></div>
										<div class=" ">
											<div class=" price-content">
												<div class="col-xs-3 col-md-3 col-sm-3 blue">
													{{$subscription_pricing->plan->title}}
												</div>
												<div class="col-xs-6 col-sm-5 col-md-5">
													<?php
													$f_id = $subscription_pricing->f_id;
													$features = explode(',', $f_id);
													$active = 'active';
													$feature_list = '';
													$sep = '';
													foreach($subscription_features as $subscription_feature) 
													{
														if(in_array($subscription_feature->id, $features)) {
															if(!empty($category_types) and $subscription_feature->functionality_name == 'category_dependent')
															{
																foreach($category_types as $category_type) {
																	$feature_list .= $sep . $category_type->name;
																	$sep = ', ';
																}
															}
															else {
																$feature_list .= $sep . $subscription_feature->title;
																$sep = ', ';
															}
														}
													}
													
													echo $feature_list;
													?>
												</div>
												<div class="col-xs-3  col-sm-3 col-md-3 orange">
													{{$currency->symbol}}{{$subscription_pricing->price}}
													@if($plan_change_free_subscription_pricing_id != $subscription_pricing->id)
														<div>
															For 
															{{$subscription_pricing->duration->title}}
															@if($subscription_pricing->duration->days_month=='1') Days @else Month @endif
														</div>
													@endif
												</div>
											</div>
										</div>

									</div>
								</a>
							</div>
						@endforeach
					</div>

					<!-- *************Subcription duriation************** -->
					<div class="col-md-2 col-sm-3 col-xs-12 text-center pad20 subdur" @if($last_subscription->subscription_pricing_id == $plan_change_free_subscription_pricing_id) style="display:none;" @endif>
						<h4>Duration</h4>
						<?php
						$i = 1;
						$subscription_term_id = '';
						?>
						@foreach($subscription_terms as $term)
							<?php
							if($i == 1)
								$subscription_term_id = $term->id;
							?>
						<div class="divbox">

							<a href="javascript:;" class="subscription_terms @if($i == 1) active @endif" data-id="{{$term->id}}" data-type="{{$term->term_type}}" data-value="{{$term->term_value}}">
								<span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>

								<div class="  wrapperbox text-center">
									<div class="duration-content">
										<h6 class=" ">{{$term->term_value}}</h6>
										<span><?php echo ucfirst(trim(str_ireplace($term->term_value, '', $term->display_value)));?></span>
									</div>

								</div>
							</a>
						</div>
						<?php $i++;?>
						@endforeach

					</div>

				</div>
			</div>
<br />
			<div class="row">
				<!-- ===========payment============ -->

				<div class="col-md-12  col-md-offset-1 paymentsel" @if($last_subscription->subscription_pricing_id == $plan_change_free_subscription_pricing_id) style="display:none;" @endif>
					<div class="col-md-12"><span class="pay-text">Payment Method</span></div>
					<?php /*<div class="col-md-2 col-md-offset-1  ">
						<div class="divbox">
							<a href="javascript:;" class="select_payment_method active" data-id="{{$cash_on_delivery}}">
								<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>

								<div class="  wrapperbox">
									<div class="pay-content">
										<div><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
										<span>Cash On Delivery</span>
									</div>
								</div>
							</a>
						</div>
					</div>*/?>
					<div class="col-md-2 col-md-offset-1">
						<div class="divbox">
							<a href="javascript:;" class="select_payment_method" data-id="{{$payment_gateway_id}}">
								<span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>

								<div class="  wrapperbox">
									<div class="pay-content">
										<div><i class="fa fa-info-circle  " aria-hidden="true"></i></div>
										<span>Net Banking</span>
									</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-2 col-md-offset-1  ">
						<div class="divbox">
							<a href="javascript:;" class="select_payment_method" data-id="{{$payment_gateway_id}}">
								<span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>

								<div class="  wrapperbox">
									<div class="pay-content">
										<div><i class="fa fa-info-circle  " aria-hidden="true"></i></div>
										<span>Credit Card</span>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>

			</div>
			<!-- ===================Save Button================= -->

			<div class="col-md-12 col-sm-12 col-xs-12 mt10" style="margin-bottom:30px;">
				
				
				
				<input type="hidden" name="payment_gateway_id" id="payment_gateway_id" value="" ><?php /*{{$cash_on_delivery}}*/?>
				<input type="hidden" name="subscription_term_id" id="subscription_term_id" value="{{$subscription_term_id}}" >
				<input type="hidden" name="subscription_pricing_id" id="subscription_pricing_id" value="@if($last_subscription->subscription_pricing_id != $free_subscription_pricing_id){{$subscription_pricing_id}}@endif">
				<input type="hidden" name="unit_price" id="unit_price" value="{{$last_subscription->paid_amount}}">
				{{ csrf_field() }}
								
				<button type="button" data-action_url="{{url('merchant/change-subscription-plan-confirm')}}" class="subscription_submit btn btn-cus save-btn">Change To Selected Plan</button> <i class="fa fa-info-circle info" aria-hidden="true" data-toggle="tooltip"  data-html="true" data-placement="top" title="1) If the selected plan is your current plan then it will be renewed and it will be effective on the expiry date of current plan Or today date, whichever is latest. <br>2) If the selected plan is other than your current plan then it will be over-write to selected plan and it will be effective on today itself."></i>
				
				<?php /*<button type="button" data-action_url="{{url('merchant/renew-subscription')}}" class="subscription_submit btn btn-cus save-btn">Renew Current Plan</button> <i class="fa fa-info-circle info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Your current plan will be renewed to selected plan. It will be effective on expiry date of current plan."></i>*/?>
				
			</div>

		</div>
		<!-- ***********************Subcription History***************** -->

		@include('panels.merchant.'.$merchant_layout.'.subscription.history')
		
		
	</div>
	</form>
	
@stop
	
@section('footer')

@include('panels.merchant.'.$merchant_layout.'.subscription.history_detail')

<script>
// Add Blog
    $(document).on("click", ".add_blog", add_blog);
	function add_blog(){ 
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

// EDit Blog
 $(document).on("click", ".edit_blog", edit_blogs);
	function edit_blogs(){ 
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/subscription/plan/getplan/') }}";
	$('#add_div').hide();
	$('#edit_div').fadeIn("slow");
	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:renderListform
	
	})
	return false;
}
function renderListform(res){ 
	$('#edit_id').val(res.view_details.id);
	$('#edit_title').val(res.view_details.title);	
}

 $(document).on("click", ".delete_blog", deleteblog);
	function deleteblog(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/subscription/plan/deleted/') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:deleteStatus
	
	})
	return false;
}
function deleteStatus(res){ 
 if (confirm("Are you sure delete plan?")) {
			var id=res.delete_details.deletedid;
			 $('.rm'+id).hide();
			$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

			}

    return false;
    }
    </script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one plan');
		return false;
	}	
}
$(document).ready(function(){
    /******************tab show or hide***********/
	$("#subscriptionTabs a").click(function(e){
		e.preventDefault();			
		$(this).tab('show');
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
	});
	
	$('#check_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#check_all').prop('checked',true);
        }else{
            $('#check_all').prop('checked',false);
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
	
	$(document).on("click", ".subscription_terms", function() {
		$type = $(this).data('type');
		$id = $(this).data('id');
		
		// reset
		$('.subscription_terms').removeClass('active');
		
		$(this).addClass('active');
		$('input[name="subscription_term_id"]').val($id);
	});
	
	$(document).on("click", ".select_subscription", function() {
		$unit_price = $(this).data('unit_price');
		$id = $(this).data('id');
		
		// reset
		$('.select_subscription').removeClass('active');
		$('input[name="subscription_pricing_id"]').val('');
		
		$(this).addClass('active');
		//if($unit_price != 0)
			$('input[name="subscription_pricing_id"]').val($id);
		
		if($id == '{{$plan_change_free_subscription_pricing_id}}') {
			$('.subdur').hide();
			$('.paymentsel').hide();
		} else {
			$('.subdur').show();
			$('.paymentsel').show();
		}
	});
	
	$(document).on("click", ".select_payment_method", function() {
		$id = $(this).data('id');
		
		// reset
		$('.select_payment_method').removeClass('active');
		
		$(this).addClass('active');
		$('input[name="payment_gateway_id"]').val($id);
	});
	
	$(document).on("click", ".subscription_submit", function() {
		$action_url = $(this).data('action_url');
		$('#subscriptionForm').attr('action', $action_url);
		$('#subscriptionForm').submit();
		
		<?php /*$payment_gateway_id = $('payment_gateway_id').val();
		$subscription_term_id = $('subscription_term_id').val();
		$subscription_pricing_id = $('subscription_pricing_id').val();
		$unit_price = $('unit_price').val();
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		$.ajax({
			type: 'POST',
			data:{'payment_gateway_id': $payment_gateway_id, subscription_term_id: $subscription_term_id, subscription_pricing_id: $subscription_pricing_id, unit_price: $unit_price, '_token':CSRF_TOKEN},
			url: host,
			dataType: "json", // data type of response		
			beforeSend: function(){
			$('.image_loader').show();
			},
			complete: function(){
			$('.image_loader').hide();
			},
			success: function(res) {
				
			}
		
		});*/?>
	});
	
	$(document).on("click", ".view_order", view_order);
	function view_order(){ 
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 

		var host="{{ url('merchant/subscription-history/vieworder/') }}";
		$('#add_div').hide();
		$('#edit_div').hide("slow");
		$('#vieworder').fadeIn("slow");
		
		
		$(".editpro .alert-danger").addClass('hidden') ;
		$(".editpro .alert-success").addClass('hidden') ;
		$.ajax({
			type: 'POST',
			data:{'id': id,'_token':CSRF_TOKEN},
			url: host,
			dataType: "json", // data type of response		
			beforeSend: function(){
			$('.image_loader').show();
			},
			complete: function(){
			$('.image_loader').hide();
			},success:renderListform
		
		})
		return false;
	}
	function renderListform(res)
	{
		var order_product = res.view_details.order_product;
		var subscription_category = res.view_details.subscription_category;
		var plan_detail = res.view_details.plan_detail;
		var currency = res.view_details.currency;
		var merchant = res.view_details.merchant;
		
		$('#order_id').html(order_product.id);
		$('#category').html(plan_detail.subscription_category.title);
		$('#payment_status').html(order_product.payment_status);
		$('#paid_amount').html(currency.symbol+(order_product.paid_amount));
		$('#plan_name').html(plan_detail.subscription_pricing.plan + ' Plan');
		//$('#merchant_name').html('Name: ' + merchant.first_name);
		$('#subscribed_term').html('Term: ' + plan_detail.subscription_term.display_value);
		//$('#email').html('Email: ' + merchant.email);
		//$('#phone_no').html('Mobile: ' + merchant.mobile_no);
		$('#subscriped_date').html('Subscriped Date: ' + order_product.subscribed_date);
		$('#expiry_date').html('Expiry Date: ' + order_product.expired_date);
		//$('#city').html('City Name: ' + merchant.city_name);
		//$('#registration_date').html('Member Since: ' + order_product.created_at);
		//$('#subscriptions_count').html('Total Subscriptions: ' + res.view_details.subscriptions_count);
		
		$('#exampleModal').modal('show');
	}
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

@stop