@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Subscription History</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-7 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/merchants/subscription-history/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Orders <span class="pull-right">  
									<button  class="btn btn-danger btn-xs hidden"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th class="hidden">
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>
							<th>Date</th>						 
							<th>Merchant Name</th>
							<th>Payment</th>
							<th>Plan</th>
							<th>Status</th>   
						   <th>Action</th>                        
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($subscribers as $subscriber)
                        <?php
							$plan_detail = json_decode($subscriber->plan_detail);
							
							$price = $plan_detail->subscription_pricing->price;
							if($subscriber->currency_id)
								$price = $subscriber->currency->symbol.$price;
							$price = 'Price: '.$price;
							
							$plan = $plan_detail->subscription_pricing->plan.', '.$price.' For '.$plan_detail->subscription_term->display_value. ', '.$plan_detail->subscription_category->title;
						?>
						<tr class="rm{{ $subscriber->id }}">
							<td class="hidden">
							<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $subscriber->id }}"/>				 	  
							</td>
							<td><?php echo date('M d y', strtotime($subscriber->created_at));?></td>
							<td>{{isset($subscriber->merchant->first_name) ? $subscriber->merchant->first_name : ''}}</td>
							<td>
								@if($subscriber->payment_gateway_id)
									<?php
									if( isset($subscriber->payment_gateway->name))
										echo $subscriber->payment_gateway->name;
									?>
								@else
									Free
								@endif
							</td>
							<td>{{$plan}}</td>
							<td>{{$subscriber->payment_status}}</td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="view_order btn btn-info btn-xs" id="{{ $subscriber->id }}" ><i class="fa fa-folder"></i> View detail </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog hidden" id="{{ $subscriber->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
                        </tr> 
					@endforeach
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-5 col-xs-12">
						
						<div class="x_panel" id="vieworder" style=" display:none">
							<h2>View Subscriber Detail </h2>
							<div class="x_title">
							</div>					
							
							<table class="table table-striped table-bordered bulk_action">
							   
							   <tr>
								<th>Subscription Id</th>
							   <th>Subscribed Category</th>
							   </tr>
							   <tr>
							   <td id="order_id"></td>
							   <td id="category"></td>
							   </tr>
							   
							   <tr>
							   <th>Payment Status</th>
								<th>Paid Amount</th>
							   </tr>
							   <tr>
							   <td id="payment_status"></td>
							   <td id="paid_amount"></td>
							   </tr>
							</table>
	 
							<table class="table table-striped table-bordered bulk_action">
						   
							   <tr>
									<th>Merchant Detail</th>	
									<th>Plan Detail</th>						  
							   </tr>
							   <tr>
									<td id="merchant_name"></td>
									<td id="plan_name"></td>						 
							   </tr>
								<tr>
									<td id="email"></td>			
									<td id="subscribed_term"></td>			 
							   </tr>
								<tr>
									<td id="phone_no"></td>			
									<td id="subscriped_date"></td>			 
							   </tr>
								<tr>
									<td id="city"></td>			
									<td id="expiry_date"></td>			 
							   </tr>
								<tr>
									<td id="registration_date"></td>
									<td></td>						 
							   </tr>
								<tr>
									<td id="subscriptions_count"></td>
									<td></td>						 
							   </tr>
							 </table>
							 
							 <?php /*<div class="form-group">
								<a class="btn btn-success" href="">Refund</a>
							  </div>*/?>
	 
                
						</div>
								
					</div>
					 <!-- Right BAR End-->
					<!--</div>
					 </div>-->
<div class="clearfix"></div>  
			</div>
    </div>
	
	<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Product </h4>
      </div>
      <div class="modal-body" id="viewItem">
       
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>
</div>

	
<script>
function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
// Add Blog
    $(document).on("click", ".add_blog", add_blog);
	function add_blog(){  	
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

$(document).on("click", ".view_order", view_order);
	function view_order(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/merchants/subscription-history/vieworder/') }}";
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
	
	var paid_amount = order_product.paid_amount;
	if(order_product.currency_id)
		paid_amount = currency.symbol+paid_amount;
	
	$('#order_id').html(order_product.id);
	$('#category').html(plan_detail.subscription_category.title);
	$('#payment_status').html(order_product.payment_status);
	$('#paid_amount').html(paid_amount);
	$('#plan_name').html(plan_detail.subscription_pricing.plan + ' Plan');
	$('#merchant_name').html('Name: ' + merchant.first_name);
	$('#subscribed_term').html('Term: ' + plan_detail.subscription_term.display_value);
	$('#email').html('Email: ' + merchant.email);
	$('#phone_no').html('Mobile: ' + merchant.mobile_no);
	$('#subscriped_date').html('Subscriped Date: ' + order_product.subscribed_date);
	$('#expiry_date').html('Expiry Date: ' + order_product.expired_date);
	$('#city').html('City Name: ' + merchant.city_name);
	$('#registration_date').html('Member Since: ' + order_product.created_at);
	$('#subscriptions_count').html('Total Subscriptions: ' + res.view_details.subscriptions_count);
}

//$('#myModal').modal('show')

$(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	
	if (confirm("Are you sure delete subscriber detail?")) 
	{
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host="{{ url('admin/merchants/subscription-history/deleted/') }}";
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
			},
			success: function(res)
			{
				if(res.success)
				{
					window.location = "{{ url('admin/merchants/subscription-history') }}";
				}
				else
				{
					alert(res.msg);
				}

			}
		
		})
		return false;
	}
    return false;
}
</script>

<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one row');
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