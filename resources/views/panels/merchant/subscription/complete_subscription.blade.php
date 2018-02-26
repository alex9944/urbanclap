@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Renew Subscription</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">
					
					 <!-- Right BAR Start-->
					<div class="col-md-11 col-xs-12">
						
						<div class="x_panel">
						
							@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
							<div class="alert alert-success hidden"></div>
								
							<h2>Plan Detail </h2>
							<div class="x_title"></div>					
							<?php
							$plan_detail = json_decode($last_subscription->plan_detail);
							?>
							<table class="table table-striped table-bordered bulk_action">
							   
							   <tr>
								<th>Subscription Id</th>
							   <th>Subscribed Category</th>
							   </tr>
							   <tr>
							   <td>{{$last_subscription->id}}</td>
							   <td>{{$plan_detail->subscription_category->title}}</td>
							   </tr>
							   
							   <tr>
							   <th>Payment Status</th>
								<th>Paid Amount</th>
							   </tr>
							   <tr>
							   <td>{{$last_subscription->payment_status}}</td>
							   <td>{{$last_subscription->currency->symbol . $last_subscription->paid_amount}}</td>
							   </tr>
							</table>
	 
							<table class="table table-striped table-bordered bulk_action">
						   
							   <tr>	
									<th>Plan Detail</th>						  
							   </tr>
							   <tr>
									<td>{{$plan_detail->subscription_pricing->plan . ' Plan'}}</td>						 
							   </tr>
								<tr>		
									<td>{{'Term: ' . $plan_detail->subscription_term->display_value}}</td>			 
							   </tr>
								<tr>	
									<td>{{'Subscriped Date: ' . $last_subscription->subscribed_date}}</td>			 
							   </tr>
								<tr>
									<td>{{'Expiry Date: ' . $last_subscription->expired_date}}</td>			 
							   </tr>
							   <tr>
									<td>Remaining Days: 
									<?php
									$expired_date_time = strtotime($last_subscription->expired_date); // or your date as well
									$today_time = time();
									$datediff = $expired_date_time - $today_time;

									echo floor($datediff / (60 * 60 * 24));
									?>
									</td>
							   </tr>
							 </table>
						
						<div class="row">
							<h2>Change/Renew My Plan</h2>
							<div class="x_title"></div>
							<?php
								$subscription_pricing_id = $last_subscription->subscription_pricing_id;
								$lower_plan = true;
								$show_submit_button = false;
							?>
							<form name="subscriptionForm" action="" id="subscriptionForm" method="post" role="form">
							@foreach($subscription_pricings as $subscription_pricing)
								<?php
								$subscription_box_style = ' style="border: #e4e3e2 1px solid"';
								$plan_info = $subscription_pricing->plan->title . ' - ' . 
											$currency->symbol . $subscription_pricing->price .
											' for ' . $subscription_pricing->duration->title;
								if($subscription_pricing->duration->days_month == '1')
									$plan_info .= ' Days';
								else
									$plan_info .= ' Month';
											
								if($subscription_pricing_id == $subscription_pricing->id) {
									$subscription_box_style = ' style="border: #337ab7 3px solid"';
								}
								
								?>
							<div class="col-xs-12 col-md-4">							
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
											?>	
												<?php 
												if(!empty($category_types) and $subscription_feature->functionality_name == 'category_dependent'):
												?>
													<?php foreach($category_types as $category_type):?>
													<tr class="{{$active}}">
														<td><?php echo $category_type->name;?>
														</td>
													</tr>
													<?php 													
														if($active == '')
															$active = 'active';
														else
															$active = '';
													endforeach;?>
												<?php
												else:
												?>
													<tr class="{{$active}}">
														<td><?php echo $subscription_feature->title;?>
														</td>
													</tr>
												<?php
												endif;
												?>
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
									@if(!$lower_plan)
										<?php
										$show_submit_button = true;
										?>
									<div class="panel-footer">
										<a href="javascript:;" data-id="{{$subscription_pricing->id}}" data-unit_price="{{$subscription_pricing->price}}" class="btn btn-default select_subscription" role="button" data-plan_info="{{$plan_info}}">Select</a></div>
									@endif
								</div>
							</div>
								<?php
								if($subscription_pricing_id == $subscription_pricing->id) {
									$lower_plan = false;
								}
								?>
							@endforeach
							
							<div style="clear:both;">
								
								<div class="next_3_validation alert alert-danger hidden"></div>

								<ul class="list-inline pull-right">
									@if($show_submit_button)
									<li>
										<button type="button" class="btn btn-default next_3">Change To Selected Plan</button>
									</li>
									@endif
									<li>
										<a href="{{url('merchant/renew-subscription')}}" class="btn btn-default next_3">Renew Current Plan</a>
									</li>
								</ul>
							</div>
							
							<input type="hidden" name="subscription_pricing_id" id="subscription_pricing_id" value="{{$subscription_pricing_id}}">
							<input type="hidden" name="unit_price" id="unit_price" value="{{old('unit_price')}}">
							</form>

						</div>
								  
								  
						</div>
								
								
								
					</div>
					 <!-- Right BAR End-->
					<!--</div>
					 </div>-->
<div class="clearfix"></div>  
			</div>
    </div>
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
});
</script>

@stop