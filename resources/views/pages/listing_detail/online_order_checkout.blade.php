	@extends('layouts.main')
@section('head')
<style type="text/css">
 	
 	.checkbox label:after, 
 	.radio label:after {
 		content: '';
 		display: table;
 		clear: both;
 	}

 	.checkbox .cr,
 	.radio .cr {
 		position: relative;
 		display: inline-block;
 		border: 1px solid #337ab7;
 		box-shadow: 0px 0px 8px 1px #337ab7;
 		border-radius: .25em;
 		width: 1.5em;
 		height: 1.5em;
 		float: left;
 		margin-right: .5em;
 		color: #ff7e00;
 	}

 	.radio .cr {
 		border-radius: 50%;
 		font-size: 16px;
 	}
 	.radio label ,.checkbox label{
 		line-height: 15px;
 		font-size: 17px;
 		padding-left:0px;
 	}
 	.checkbox .cr .cr-icon,
 	.radio .cr .cr-icon {
 		position: absolute;
 		font-size: .8em;
 		line-height: 0;
 		top: 50%;
 		left: 16%;
 	}

 	.radio .cr .cr-icon {
 		margin-left: 0.04em;
 	}

 	.checkbox label input[type="checkbox"],
 	.radio label input[type="radio"] {
 		display: none;
 	}

 	.checkbox label input[type="checkbox"] + .cr > .cr-icon,
 	.radio label input[type="radio"] + .cr > .cr-icon {
 		transform: scale(3) rotateZ(-20deg);
 		opacity: 0;
 		transition: all .3s ease-in;
 	}

 	.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
 	.radio label input[type="radio"]:checked + .cr > .cr-icon {
 		transform: scale(0.9) rotateZ(0deg);
 		opacity: 1;
 	}

 	.checkbox label input[type="checkbox"]:disabled + .cr,
 	.radio label input[type="radio"]:disabled + .cr {
 		opacity: .5;
 	}
 	.address-text{
 		position:relative;
 		   display: block;
    width: 100%;
    min-height: 100px;
 	}
 	.address-text p{
 		line-height: 7px;
 		padding-left: 15px;
 		font-size: 15px;
 		font-weight: 600;
 		margin-top: 15px;
 	}
 	.address-text .checkbox{
 		display:inline-block;
 		    top: 0px;
    position: absolute;
 	}
 	.address-text .address-box{
 	display:inline-block;
    left: 0px;
    top: 10px;
    position: relative;
    
 	}
 	
 	.pd30{
 		padding: 30px;
 	}
 	.green-select{
 		background: #05880d;
 		border: 1px solid #05880d;
 		color: white;
 	}
 	.price-text span{
 		font-size: 17px;
 		font-weight: 600;

 	}
 	.price-text span.ltr{
 		direction: ltr;

 		-moz-margin-start :57%;
 		-webkit-margin-start :57%;
 	}
 	@media only screen and (min-width:320px) and (max-width:450px){
 		.price-text span.ltr{
 			-moz-margin-start :12%;
 		-webkit-margin-start :12%;
 		}
 	}
 	
 	/*.address-content{
 		 
 	}*/
 	.address-content label{
 		color: #0e0d0d;
 		font-weight: 600;
 	}
 	.blue-btn{
 		color: #fff;
 		background-color: #337ab7;
 		border-color: #2e6da4;
 	}
 	.border-right{
 		border-right:1px solid #eee;
 	}
 	.partition-head{
 		    width: 50%;
    display: block;
    margin: 0 auto;
    padding: 20px;
    background-color: #1b7fc4;
    border-color: #1b7fc4;
    color:#fff;
 	}
 	@media only screen and (max-width:768px){
 		.partition-head{		
 		width: 100%;
   		
 	}
 	}
 	.demo-content{ 		
 		font-size: 15px;
 		min-height: 300px; 		
 		border: 2px solid #dbdfe5;
 		margin-bottom: 10px;
		display: table;
        width: 100%;
 	}
 	table{
 		width: 100%;
 		border: 1px solid #fff;
 	}
 	table.price-table, .price-table th, .price-table td,.price-table  tr, .table>thead>tr>th{
 		line-height: 41px;
 	}
 	table, th, td, tr, .table>thead>tr>th{

 		border: none;
 	}
 	.title-text {
    color: #0faaf1;
    font-size: 3.5vh;
    font-weight: 600;
    padding: 15px;
}
.error-alerts{
	margin-left:20px;
}
.radio label{
	margin-right:10px;
	padding-top:5px;
}
 </style>
@stop
@section('content')
@include('partials.status-panel')




<?php
$cart_items = \Cart::getContent();
?>

<!-- Main content start -->
	<div class="container">

     <?php 
		$cart_exists = true;
		if (! empty($cart_items_split_by_merchant)) {
			$i = 0;
		?>

		<?php if(!$user_id):?>
			<div class="col-md-12">
			<div class="row m-tb50">
			<div class="col-sm-4 col-md-4"></div>
			<div class="col-sm-4 col-md-4 text-center">
				<span style="vertical-align: middle;">
				Checkout as Guest <b>Or</b> </span>
				<a href="<?php echo url('login');?>" class="btn btn--ys btn-default" style="margin-top:0px;">
				Login
				</a>					
			</div>
			<div class="col-sm-4 col-md-4"></div>
			</div>
			</div>
		<?php endif;?>
		
		<div class="col-md-12">
		@if(Session::get('message')) 
			<div class="col-md-12 m-t50"><div class="alert alert-success" role="alert">{{Session::get('message')}} </div></div>
		@endif
		@if(Session::get('error_message')) 
			<div class="col-md-12 m-t50"><div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div></div>
		@endif
		
		@if ($errors->any())
		<div class="row">
			<div class="alert alert-danger">
				<ul class="error-alerts">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		</div>
		@endif
		</div>
		
		<!-- content-tab start -->
		<form name="placeOnlineOrderFrm" action="{{ url('online-order/place-order') }}" method="post">
		<section class="tab-content-details" style="margin-top:20px;">
			<div class="container">
				<div class="row">
					<div class="col-md-12 ">
						<div class="col-md-6 border-right">
							<div class="text-center">
							  <span class="partition-head">Delivery Address</span>
							</div>
							<?php 
		                        $delivery_address_section = true;
		                        $billing_detail_id = '';
		                       if(is_object($billing_details) and $billing_details->count() != 0):?>
							
							<div class="pd30 address-content">
								<div class="select-address">

									<!-- -->
									<strong>Select Address</strong>
									<!--  -->


								</div>
								
								<div class="address-text">
								<?php
						$address = old('address');
						$existing_address = ' checked';
						$delivery_address_section = false;
						$new_address = '';						
						if($address == 'New Address') {
							$new_address = ' checked';
							$existing_address = '';
							$delivery_address_section = true;
						}
						?> 
						
							<div class="radio"> 
							
							<div class="form-group">
								<label>
								<input type="radio" name="address" class="getaddress" value="Existing Address"<?php echo $existing_address;?> > Existing Address
								<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
								</label>
								<label>
								<input type="radio" name="address"class=" getaddress" value="New Address"<?php echo $new_address;?> > New Address
								<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
								</label>
							</div>
							</div> 
						
							<div  id="show_address">
							@foreach($billing_details as $key => $billing_detail)
							<?php
							$checked = '';
							if( old('billing_detail_id') == $billing_detail->id || (old('billing_detail_id') == '' and $key == 0) ){
								$checked = 'checked';
								$billing_detail_id = $billing_detail->id;
							}
							?>
										
									

									
									
									<div class="address-box">
									<input type="radio" name="use" value="{{$billing_detail->id}}" class="useraddress" {{$checked}} />
									<div class="address-texts">
									<p>{{$billing_detail->s_name}}</p>
									<p>{{$billing_detail->s_address_1}}
									<p>@if($billing_detail->s_address_2)</p>
									<p>{{$billing_detail->s_address_2}}@endif </p>
									<p>{{$billing_detail->delivery_city->name}}</p>
									<p>{{$billing_detail->s_pincode}}</p>
									</div>
									</div>
								
								@endforeach
								</div>
							  </div>
							</div>
							
						<?php endif ;?>
							
							
						<input type="hidden" name="billing_detail_id" id="billing_detail_id" value="{{old('billing_detail_id', $billing_detail_id)}}" >
						
					<div id="delivery_address_section" class="row"<?php if(!$delivery_address_section):?> style="display:none;"<?php endif;?>>
							<div class="pd30 form-box col-md-12">
							
								<h5>Add New Address</h5>
								
									<div class="form-group">

										<input type="text" name="s_name" id="s_name" class="form-control" value="<?php echo old('s_name');?>" placeholder="Name">
									</div>
									<div class="form-group">

										<input type="text" name="s_company" id="s_company" class="form-control" value="<?php echo old('s_company');?>" placeholder="Company">
									</div>
									<div class="form-group">

										<input type="text" name="s_address_1" id="s_address_1" value="<?php echo old('s_address_1');?>" class="form-control" placeholder="Address 1"/>
									</div>
									<div class="form-group">
										<input type="text" name="s_address_2" id="s_address_2" value="<?php echo old('s_address_2');?>" class="form-control" placeholder="Address 2"/>
									</div>
									<div class="form-group">
										<input type="text" name="email" id="email" value="<?php echo old('email', @$user_data->email);?>" class="form-control" placeholder="Email"/>
									</div>
									<div class="form-group">
										<input type="text" name="phone_number" id="phone_number" value="<?php echo old('phone_number', @$user_data->mobile_no);?>" class="form-control" placeholder="Phone Number"/>
									</div>
									<div class="form-group">
                                    <select id="s_state" name="s_state" class="form-control">
									<option value=""> State  </option>
								    <?php 
									foreach($s_states as $state) { 
								    ?>
									<option value="<?php echo $state->id;?>" @if(old('s_state') == $state->id) selected @endif>
										<?php echo $state->name;?>
									</option>
								    <?php } ?>
								</select>
								</div>
									<div class="form-group">
                                     <select id="s_city" name="s_city" class="form-control">
									<option value="">  City </option>
									<?php 
									foreach($s_cities as $city) { 
									?>
									<option value="<?php echo $city->id;?>" 
									@if(old('s_city') == $city->id) selected @endif>
										<?php echo $city->name;?>
									</option>
									<?php } ?>
								    </select>
										
									</div>
									
								
								<div class="form-group">

									<input type="text" name="s_pincode" class="form-control" id="s_pincode" value="<?php echo old('s_pincode');?>" placeholder="PinCode">
								</div>
                                <div class="form-group">
								
                                 <input type="hidden" name="s_country" class="form-control"  
								   value="{{ $listing_country->id }}" placeholder=""><span style="    margin-left: 15px;">{{ $listing_country->name }}</span>
								</div>

								</div>
							
						  </div>
						</div>
						<div class="col-md-6">
							<div class="text-center"><span class="partition-head">Payment Option</span></div>
							<div class="col-md-12 col-xs-12 pull-right text-center mt20">
								<div class="demo-content">
									<h4 class="title-text">Order Summary</h4>
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="price-table">
												<thead>

													<th></th>
													<th></th>

												</thead>
												<tbody>
												
												<?php 
												
												
						
						$total_shipping_amount = 0;
						foreach($cart_items_split_by_merchant as $listing_carts) { 
							$cart_items = $listing_carts['cart_item'];
							$listing = $listing_carts['listing'];
							$category_type = $listing_carts['category_type'];
							$delivery_fee = $listing_carts['delivery_fee'];
							$listing_total = 0;
							
							if($category_type == 'online_order') {	
								$total_shipping_amount += $delivery_fee;
							}
							
							$summary_arr = [];
							foreach($cart_items as $row) {	
											$shipping_price = $row->attributes['shipping_price'];
											$listing_total += $row->getPriceSum() + $shipping_price;
											$total_shipping_amount += $shipping_price;
											 $val=array('title' => $listing->title, 'amount' =>($currency->symbol.$listing_total),'shipping'=>($currency->symbol.$total_shipping_amount));			
									array_push($summary_arr, $val);
							}
							
						?>
										
										<?php 
									foreach($summary_arr as $key => $val){?>	
									<tr>		
						             <td class="text-left"><?php echo $val['title']; ?></td>
									 <td class="text-right"><?php echo $val['amount']; ?></td>
									</tr> 
									<?php } ?>
									 	
													<!--<tr>
													    <td class="text-left">Delivery</td>
														<td class="text-right">
															@if($delivery_fee and $category_type == 'online_order')
										                     <?php echo $currency->symbol . ' '. $delivery_fee;$delivery_fee = '';?>
									                         @endif
														</td>
													</tr>	-->
													
						                 <?php   } ?>
														   
												</tbody>
												<tfoot>
													
													<tr>
														<td class="text-left"><strong>Shipping & Delivery Fee </strong></td>
														<td class="text-right"><strong><?php echo $currency->symbol . ' ' . $total_shipping_amount;?></strong></td>
													</tr>
													
													<tr>
													<?php $total = \Cart::getTotal() + $total_shipping_amount;
													?>
													
														<td class="text-left"><strong>Total amount</strong></td>
														<td class="text-right"><strong><?php echo $currency->symbol . ' ' . $total;?></strong></td>
													</tr>
																										
												</tfoot>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!--<div class="pd30 col-sm-12">
								<div class="price-text">
									<div class="mt20">
										<div class="radio">
											<label>
												<input type="radio" name="o2" value="" checked>
												<span class="cr"><i class="cr-icon glyphicon glyphicon-arrow-right"></i></span>
												Cash On Delivery
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="o2" value="" >
												<span class="cr"><i class="cr-icon glyphicon glyphicon-arrow-right"></i></span>
												Net Banking
											</label>
										</div>
										<div class="radio  ">
											<label>
												<input type="radio" name="o2" value=""  >
												<span class="cr"><i class="cr-icon glyphicon glyphicon-arrow-right"></i></span>
												Credit Card
											</label>
										</div>
									</div>
								</div>
							</div> -->
						</div>
					</div>
				</div>
				<div class="row text-center">
				   <input type="hidden" name="payment_gateway_id" value="{{$payment_gateway->id}}" >
				   <button type="submit" name="save_order" class="  btn btn-primary green text-center" style="margin-bottom: 10px;">Place Order Now</button>	
					
					<!--<div class="col-md-12 text-center " style="margin-bottom: 10px;"> <a href="" class="btn btn-primary green">Proceed to Payment</a></div> -->
				</div>
				
			</div>
		</section>
		{{ csrf_field() }}
        </form>
<?php } ?>
		<!-- content-tab end -->
		
	</div>

	<!-- Main content end -->

@stop
								
@section('footer')	
<script type="text/javascript">
	$(document).ready(function () {
		$('#copy_billing_details').click(function()
		{
			$('.billing_address').each(function()
			{
				// Target textboxes only, no hidden fields
				if ($(this).attr('type') == 'text')
				{
					var name = $(this).attr('name').replace('b_', 's_');
					var value = ($('#copy_billing_details').is(':checked')) ? $(this).val() : '';
					
					$('input[name="'+name+'"]').val(value);
				}
				else if( $(this).attr('name') == 'b_state' )
				{
					var value = ($('#copy_billing_details').is(':checked')) ? $(this).val() : '';
					$('#s_state').val(value);
				}
				else if( $(this).attr('name') == 'b_city' )
				{
					var value = ($('#copy_billing_details').is(':checked')) ? $(this).val() : '';
					var b_city = ($('#copy_billing_details').is(':checked')) ? $(this).html() : '<option value=""> - City / Town - </option>';
					
					$('#s_city').html(b_city);
					$('#s_city').val(value);
				}
			});
		
		});
	});
	
	$(document).on("change", "#b_state", online_order_cities);
	$(document).on("change", "#s_state", online_order_cities);
	function online_order_cities(){ 
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id = $(this).val();
		var type = $(this).attr('id');
		
		var city_var = 's_city';
		if( type == 'b_state' )
			city_var = 'b_city';
		
		$('#'+city_var).html('<option value="">  City  </option>');
		
		if(id == '')
			return false;

		var host="{{ url('getcities') }}";	
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
			},success: function(res) {
				render_online_order_cities(res, type);
			}

		})
		return false;
	}
	function render_online_order_cities(res, type){

		var city_var = 's_city';
		if( type == 'b_state' )
			city_var = 'b_city';
		
		$.each(res.view_details, function(index, data) {
			$('#'+city_var).append('<option value="'+data.id+'">'+data.name+'</option>');
		});   
	}
	
	$(document).on("change", ".getaddress", function() {
		$address = $(this).val();
		if($address == 'Existing Address')
		{
			$('#show_address').show();
			$('#delivery_address_section').hide();
		}
		else
		{
			$('#show_address').hide();
			$('#delivery_address_section').show();
			//$('#billing_detail_id').val('');
			//$('.useraddress').prop('checked', false);
		}
	});

	$(document).on("click", ".useraddress", function() {
		$('#billing_detail_id').val($(this).val());
	});
</script>
@stop