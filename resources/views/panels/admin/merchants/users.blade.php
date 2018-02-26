@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Merchant Users</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-6 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/merchants/users/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Merchants Users <span class="pull-right"><a href="{{url('admin/merchants/users')}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add </a>  
									<?php /*<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>*/?>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><?php /*<th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>	*/?>					 
                          <th>ID</th>					 
						  <th>Date</th>	
                          <th>Name</th>					 
                          <th>Email</th>			 
                          <th>Status</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($users as $user)
                        <tr class="rm{{ $user->uid }}">
                          <?php /*<td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $user->uid }}"/>				 	  
						  </td>*/?>
						  <td>{{ $user->id }}</td>
						  <td><?php echo date('j M y', strtotime($user->created_at));?></td>
                          <td>{{ $user->first_name }}</td>
                          <td>{{ $user->email }}</td>
                         <td>
							 @if($user->merchant_status==0)
								Pending
							 @elseif($user->merchant_status==1)
								Activated
							 @else
								 De-Activated
							 @endif
                         </td>
						 <td>
							<a href="javascript:void(0);" class="view_user btn btn-primary btn-xs" data-id="{{ $user->uid }}" ><i class="fa fa-pencil"></i> View </a>
                            <a href="javascript:void(0);" class="edit_user btn btn-info btn-xs" data-id="{{ $user->uid }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" data-id="{{ $user->uid }}"><i class="fa fa-trash-o"></i> Delete </a>
							</td>
                        </tr> 
					@endforeach
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-6 col-xs-12">
					<div class="x_panel" id="add_div" style="">
						<?php
						$id = '';
						$add = 'Add';
						$url = url('admin/merchants/users/added');
						if(old('id') != '')
						{
							$id = old('id');
							$add = 'Edit';
							$url = url('admin/merchants/users/updated');
						}
						?>
						
						<h2><span id="add_div_label">{{$add}}</span> Merchants User </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
						@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 
								  <!-- Add Form Start-->
						
						@if ($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						
						<form id="addUser" method="POST" action="{{$url}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
							<input type="hidden" value="{{old('id')}}" name="id" id="id" />
							<div id="reportArea">
								<div class="form-group required">
									<label class="col-sm-2 control-label">Category</label>
									<div class="col-sm-6">
										<select name="category_id" id="category_id" class="form-control category">
											<option value="">Category</option>
											@foreach($categories as $category)
												<option value="{{$category->c_id}}" @if(old('category_id') == $category->c_id) selected @endif>{{$category->c_title}} </option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label">  Name</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="firstname" id="firstname" value="{{ old('firstname') }}"/>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label">Email</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="email" id="email" value="{{ old('email') }}"/>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label">Mobile</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="mobile_no" id="mobile_no" value="{{ old('mobile_no') }}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">  Website</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="website" id="website" value="{{ old('website') }}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">  Bio</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="bio" id="bio" value="{{ old('bio') }}"/>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label">Password</label>
									<div class="col-sm-6">
										<input class="form-control" type="password" name="password" id="password" value=""/>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label">Confirm Password</label>
									<div class="col-sm-6">
										<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value=""/>
									</div>
								</div>
								
								 <div class="form-group">
									<label class="col-sm-2 control-label">Profile Image</label>
									<div class="col-sm-6">
										<input class="form-control" type="file" name="photo" id="photo"  />
									</div>
								</div>	
								
								<div class="form-group required">
									<label class="col-sm-2 control-label">Status</label>
									<div class="col-sm-6">
										<select name="merchant_status" id="merchant_status" class="form-control">
											<option value="1" @if( old('merchant_status') == 1 ) selected @endif>Activated</option>
											<option value="2" @if( old('merchant_status') === 2 ) selected @endif>De-Activated</option>
											<option value="0" @if( old('merchant_status') === 0 ) selected @endif>Pending</option>
										</select>
									</div>
								</div>
								
								
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="ln_solid"></div>
									  <div class="form-group">
										<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
										  <input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>
										
										</div>
									  </div>
									  
									  
							   
								<div class="clearfix visible-lg"></div>
							</div>
						</form>
								  
												  
								  <!-- Add Form End-->
								  
								  
								  
								  
								</div>
								
					<div class="x_panel" id="edit_div" style=" display:none">
						<h2>View Merchant User </h2>
						<div class="x_title">
						</div>	
						
						 
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#user">Profile</a></li>
							<li><a data-toggle="tab" href="#listing">Listing</a></li>
							<li><a data-toggle="tab" href="#active_subscription">Active Subscription</a></li>
						</ul>
								  <!-- Edit Form Start-->
						<form method="POST" action="{{url('admin/merchants/users/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
							<input type="hidden" value="" name="id" id="edit_id" />
							<input type="hidden" value="" name="subscription_id" id="subscription_id" />
                            
							<div class="tab-content">
							
								<div id="user" class="tab-pane fade in active">
									<h2>Profile</h2>
									<div class="x_title">
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label">  First Name</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" name="firstname" id="edit_firstname"  value="" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Email</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" name="email" id="edit_email" value="" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">  Mobile</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" name="mobile_no" id="edit_mobile_no" value="" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">  Website</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" name="website" id="edit_website" value="" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">  Bio</label>
										<div class="col-sm-6">
											<input class="form-control" type="text" name="bio" id="edit_bio" value="" readonly />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label">Profile Image</label>
										<div class="col-sm-6">
										 <div class="col-sm-9">
											<img src="" id="edit_photo" style="height:40px">
											</div>
											 <div class="col-sm-3">
											 </div>
											
											
										</div>
									</div>	

									<div class="form-group required">
										<label class="col-sm-2 control-label">Status</label>
										<div class="col-sm-6">
											<select name="merchant_status" id="edit_merchant_status"  class="form-control" readonly>
												<option value="1">Activated</option>
												<option value="2">De-Activated</option>
												<option value="0">Pending</option>
											</select>
										</div>
									</div>
									
									<div class="listing_status_section form-group required" style="display:none;">
										<label class="col-sm-2 control-label">Listing Status</label>
										<div class="col-sm-6">
											<select name="listing_status" id="listing_status"  class="form-control">
												<option value="">Select</option>
												<option value="Enable">Enable</option>
												<option value="Disable">Disable</option>
											</select>
										</div>
									</div>
									
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="ln_solid"></div>
										  <?php /*<div class="form-group">
											<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
												<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>
												<a class="btn btn-success" id="deactivate_refund" href="javascript:;">De-Activate & Refund</a>
											</div>
										  </div>*/?>
										  
										  
								   
									<div class="clearfix visible-lg"></div>
								</div>
								
								<div class="tab-pane fade" id="listing">
									<h2>Listing Detail </h2>
									<div class="x_title">
									</div>
									
									<table class="table table-striped table-bordered bulk_action">
										<tr>
											<th>Title</th>
											<td id="listing_title"></td>
										</tr>
										<tr>
											<th>Category</th>
											<td id="listing_category"></td>
										</tr>
										<tr>
											<th>Subcategory</th>
											<td id="listing_subcategory"></td>
										</tr>
										<tr>
											<th>Website</th>
											<td id="listing_website"></td>
										</tr>
										<tr>
											<th>Address</th>
											<td id="listing_address"></td>
										</tr>
										<tr>
											<th>Description</th>
											<td id="listing_description"></td>
										</tr>
									</table>
								</div>
								
								<div class="tab-pane fade" id="active_subscription">
									<h2>Active Subscription </h2>
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
											<th>Current Plan Detail</th>						  
									   </tr>
									   <tr>
											<td id="merchant_name"></td>
											<td id="plan_name"></td>						 
									   </tr>
										<tr>
											<td id="merchant_email"></td>			
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
			 
						
								</div>
								
							</div>
                    	</form>			  
						<!-- Edit Form End-->
								
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
	
	$('#add_div_label').html('Add');
	
	var url = "{{url('admin/merchants/users/added')}}";
	$('#addUser').attr('action', url);
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	
	$('#id').val('');
	$('#firstname').val('');
	$('#mobile_no').val('');
	$('#website').val('');
	$('#bio').val('');
    $('#photo').attr('src','');
	$('#email').val('');
	$('#merchant_status').val('0');

}
$(document).on("click", "#deactivate_refund", function(){
	var subscription_id = $('#subscription_id').val();
	if(subscription_id)
	{
		if(confirm('Are you sure want to de-activate and refund the amount for the selected vendor?'))
		{
			window.location = "{{url('admin/merchants/deactivate-and-refund')}}" + '/' + subscription_id;
		}
	}
});

// Edit
$(document).on("click", ".edit_user", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).data('id'); 
	
	var url = "{{url('admin/merchants/users/updated')}}";
	$('#addUser').attr('action', url);

	var host="{{ url('admin/merchants/users/getusers/') }}";
	$('#edit_div').hide();
	$('#add_div').show();
	
	$('#add_div_label').html('Edit');
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
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
        },success:renderEditUser
	
	})
	return false;
}
function renderEditUser(res)
{ 
	cleanAll();
	
	var users = res.view_details.users;
	
	var url="{{ url('') }}";
	var image_path=users.image;
	$('#id').val(users.id);
	$('#category_id').val(users.category_id);
	$('#firstname').val(users.first_name);
	$('#email').val(users.email);
	$('#mobile_no').val(users.mobile_no);
	$('#website').val(users.website);
	$('#bio').val(users.bio);
    $('#photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#merchant_status').val(users.merchant_status);	
}

// View
$(document).on("click", ".view_user", view_user);
function view_user(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).data('id'); 

	var host="{{ url('admin/merchants/users/getusers/') }}";
	$('#add_div').hide();
	$('#edit_div').show();
	
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
        },success:renderUser
	
	})
	return false;
}
function cleanAll()
{
	
	$('#edit_id').val('');
	$('#edit_firstname').val('');
	$('#edit_mobile_no').val('');
	$('#edit_website').val('');
	$('#edit_bio').val('');
    $('#edit_photo').attr('src','');
	$('#edit_email').val('');
	$('#edit_merchant_status').val('0');
	
	$('#listing_status').val('');
		
	$('#listing_title').html('');
	$('#listing_category').html('');
	$('#listing_subcategory').html('');
	$('#listing_website').html('');
	$('#listing_address').html('');
	$('#listing_description').html('');
	
	$('#subscription_id').val('');
	
	$('#order_id').html('');
	$('#category').html('');
	$('#payment_status').html('');
	$('#paid_amount').html('');
	$('#plan_name').html('');
	$('#merchant_name').html('');
	$('#subscribed_term').html('');
	$('#merchant_email').html('');
	$('#phone_no').html('');
	$('#subscriped_date').html('');
	$('#expiry_date').html('');
	$('#city').html('');
	$('#registration_date').html('');
	$('#subscriptions_count').html('');
}
function renderUser(res)
{ 
	cleanAll();
	
	var users = res.view_details.users;
	
	var url="{{ url('') }}";
	var image_path=users.image;
	$('#edit_id').val(users.id);
	$('#edit_firstname').val(users.first_name);
	$('#edit_mobile_no').val(users.mobile_no);
    $('#edit_photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#edit_email').val(users.email);
	$('#edit_website').val(users.website);
	$('#edit_bio').val(users.bio);
	$('#edit_merchant_status').val(users.merchant_status);
	
	var listing = res.view_details.listing;
	var listing_detail = res.view_details.listing_detail;
	$('.listing_status_section').hide();
	if(listing) {
		$('.listing_status_section').show();
		$('#listing_status').val(listing.status);
		
		$('#listing_title').html(listing.title);
		$('#listing_category').html(listing_detail.listing_category);
		$('#listing_subcategory').html(listing_detail.listing_subcategory);
		$('#listing_website').html(listing_detail.listing_website);
		$('#listing_address').html(listing_detail.listing_address);
		$('#listing_description').html(listing_detail.listing_description);
	}
	
	renderListing(res);
}
function renderListing(res)
{
	var subscriber = res.view_details.subscriber;
	if(subscriber)
	{
		var subscription_category = res.view_details.subscription_category;
		var plan_detail = res.view_details.plan_detail;
		var currency = res.view_details.currency;
		var merchant = res.view_details.merchant;
		
		var paid_amount = subscriber.paid_amount;
		if(subscriber.currency_id)
			paid_amount = currency.symbol+paid_amount;
		
		$('#subscription_id').val(subscriber.id);
		
		$('#listing_section').show();
		$('#order_id').html(subscriber.id);
		$('#category').html(plan_detail.subscription_category.title);
		$('#payment_status').html(subscriber.payment_status);
		$('#paid_amount').html(paid_amount);
		$('#plan_name').html(plan_detail.subscription_pricing.plan + ' Plan');
		$('#merchant_name').html('Name: ' + merchant.first_name);
		$('#subscribed_term').html('Term: ' + plan_detail.subscription_term.display_value);
		$('#merchant_email').html('Email: ' + merchant.email);
		$('#phone_no').html('Mobile: ' + merchant.mobile_no);
		$('#subscriped_date').html('Subscriped Date: ' + subscriber.subscribed_date);
		$('#expiry_date').html('Expiry Date: ' + subscriber.expired_date);
		$('#city').html('City Name: ' + merchant.city_name);
		$('#registration_date').html('Member Since: ' + subscriber.created_at);
		$('#subscriptions_count').html('Total Subscriptions: ' + res.view_details.subscriptions_count);
	}
}

 $(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	if (confirm("Are you sure delete user detail.?")) {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).data('id'); 
		var host="{{ url('admin/merchants/users/deleted/') }}";
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
					window.location = "{{ url('admin/merchants/users') }}";
				}
				else
				{
					alert(res.msg);
				}

			}
		
		});
	}
	return false;
}
function deleteStatus(res){ <?php /*  This action will delete all user data and user related information like subscription history, listing, */?>
 
			var id=res.delete_details.deletedid;
			 $('.rm'+id).hide();
			$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

			
    }
	
	
//Change Status Enable

 $(document).on("click", ".enable", enable);
	function enable(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/merchants/users/enable/') }}";
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
        },success:enableStatus
	
	})
	return false;
}
function enableStatus(res){
			location.reload();
			}
 	
	
	
//Change Status Disable

 $(document).on("click", ".disable", disable);
	function disable(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/merchants/users/disable/') }}";
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
        },success:disableStatus
	
	})
	return false;
}
function disableStatus(res){ 
location.reload();
    }	
		
	
    </script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one merchant');
		return false;
	} else {
		if (confirm("Are you sure delete all the selected merchants?"))
			return true;
		else
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