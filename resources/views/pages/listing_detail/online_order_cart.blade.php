@extends('layouts.main')
@section('head')
<style type="text/css">
<!-- 	.demo-content{ 		
 		font-size: 15px;
 		min-height: 300px; 		
 		border: 2px solid #dbdfe5;
 		margin-bottom: 10px;
 	}
 	.demo-content.bg-alt{
 		background: #abb1b8;
 	}
 	.input-number{
 		background: #0f7414 ;
 		color:#fff;
 		text-align: center;
 	}
 	.title-text{
 		color: #0faaf1;
 		font-size: 3.5vh;
 		font-weight: 600;
 		padding:15px;
 	}
 	.title-text small{
 		font-size: 13px;
 		margin-left: 2%;
 		font-weight: 500;
 		color: #0faaf1;
 	}
 	.btn-title{
 		width: 15%;
 		font-size: 19px;
 		font-weight: 700;
 		padding: 0px;
 		margin-bottom: 12px;
 		background: #f05736;
 		border: none;
 	}
 	.pd25{
 		padding: 25px
 	}
 	table, th, td, tr, .table>thead>tr>th{

 		border: none;
 	}
 	.table-cart{
 		overflow-x: inherit;
 	}
 	table{
 		width: 100%;
 		border: 1px solid #fff;
 	}
 	.quantySelection{
 		width: 40%;
 		margin: 0 auto;
 	}
 	.single-prod .quantySelection{
 		width: 57%;
 		margin: 0 auto;
 	}
 	.quantySelection .form-group{
 		margin:0px;
 	}
 	.btn-stock {
 		border-radius: 12px;
 		background: #208305 !important;
 		border: 1px solid #208305 ; 
 		padding: 6px 7px;

 	}
 	.btn-stock span {
 		font-size: 9px;
 		color: #208305;
 		padding: 4px 4px;
 	}
 	.delete-item{
 		padding:20px;
 	}
 	.delete-item i{
 		color:red;
 		font-weight: 500;
 	}
 	.tab-img{
 		width: 45%;
 		/*margin-left: 16px;*/
 		margin-top: 3%;
 	}
 	table.price-table, .price-table th, .price-table td,.price-table  tr, .table>thead>tr>th{
 		line-height: 41px;
 	}

 	.proceedPay a{
 		width:100%;
 		height:50px;
 		padding:6%;
 	}-->
	.demo-content{ 		
 		font-size: 15px;
 		min-height: 300px; 		
 		border: 2px solid #dbdfe5;
 		margin-bottom: 10px;
		display: table;
		width: 100%;
 	}
 	.demo-content.bg-alt{
 		background: #abb1b8;
 	}
 	.input-number{
 		background: #0f7414 ;
 		color:#fff;
 		text-align: center;
 	}
 	.title-text{
 		color: #0faaf1;
 		font-size: 3.5vh;
 		font-weight: 600;
 		padding:15px;
 	}
 	.title-text small{
 		font-size: 13px;
 		margin-left: 2%;
 		font-weight: 500;
 		color: #0faaf1;
 	}
 	.btn-title{
 		
 		font-size: 19px;
 		font-weight: 700;  		
 		background: #f05736;
 		width: 25%;
   		display: block;
    	margin: 0 auto;
    	margin-bottom: 12px;
    	padding: 20px;
    	color:#fff;
 	}
 	.pd25{
 		padding: 25px
 	}
 	table, th, td, tr, .table>thead>tr>th{

 		border: none;
 	}
 	.table-cart{
 		overflow-x: inherit;
 	}
 	table{
 		width: 100%;
 		border: 1px solid #fff;
 	}
 	.quantySelection{
 		width: 40%;
 		margin: 0 auto;
 	}
 	.single-prod .quantySelection{
 		width: 57%;
 		margin: 0 auto;
 	}
 	.quantySelection .form-group{
 		margin:0px;
 	}
 	.btn-stock {
 		border-radius: 12px;
 		background: #208305 !important;
 		border: 1px solid #208305 ; 
 		padding: 6px 7px;

 	}
 	.btn-stock span {
 		font-size: 9px;
 		color: #208305;
 		padding: 4px 4px;
 	}
 	.delete-item{
 		padding:20px;
 	}
 	.delete-item i{
 		color:red;
 		font-weight: 500;
 	}
 	.tab-img{
 		width: 45%;
 		/*margin-left: 16px;*/
 		margin-top: 3%;
 	}
 	table.price-table, .price-table th, .price-table td,.price-table  tr, .table>thead>tr>th{
 		line-height: 23px;
         padding: 10px;
 	}

 	.proceedPay a{
 		width:100%;
 		height:50px;
 		padding:6%;
 	}
 	@media only screen and (max-width:768px){
 		.btn-title{
 		
 		
 		width: 100%;
   		
 	}
 	}
 </style>
@stop
@section('content')
@include('partials.status-panel')

<?php

$cart_empty = $is_empty_online_order;

?>

<div class="main-content">



		<!-- content-tab start -->
		<section class="tab-content-details" style="margin-top:20px;">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center ">
						<span class="btn-title">My Cart</span>

					</div>
					<?php 
			if ($cart_empty):?>
			<div class="col-md-12">
				<p class="text-center">You currently have no items in your shopping cart !</p>
			</div>
		<?php endif;?>
		
		    @if(Session::get('message')) 
				<div class="col-md-12 m-t50"><div class="alert alert-success" role="alert">{{Session::get('message')}} </div></div>
			@endif
			<?php 
		$cart_exists = true;
		if (! empty($cart_items_split_by_merchant)) {
			
			?>
			<?php 
			$cart_exists = true;
			if (! $cart_empty) {
				$i = 1;
				$subtotal = \Cart::getSubTotal();
				$total_shipping_amount = 0;
			?>
			
					<div class="col-md-9">
					
					<?php 
				$summary_arr = [];
				foreach($cart_items_split_by_merchant as $listing_carts):
					$cart_items = $listing_carts['cart_item'];
					$listing = $listing_carts['listing'];
					$category_type = $listing_carts['category_type'];
					$delivery_fee = $listing_carts['delivery_fee'];						
					$listing_total = 0;
					if($category_type == 'online_order') {
						$listing_total = $delivery_fee;		
						$total_shipping_amount += $delivery_fee;
					}
					
				?>
						
						<div class="col-md-12 col-xs-12">
							<div class="demo-content">
								<h4 class="title-text">{{$listing->title}}<small> {{$listing->address1}}</small></h4>

								<div class="col-md-12">
								
									<div class="table-responsive table-cart">
										<table>
											<thead>
												<th>Item</th>
												<th></th>
												<th> @if($category_type == 'online_shopping')Shipping Charges @endif </th>
												<th></th>
												<th class="text-right"><span>Total</span></th>
											</thead>
											<tbody>
											<?php 
						foreach($cart_items as $row) { 
							//$i++;
							
							$shipping_price = $row->attributes['shipping_price'];

							$listing_total += $row->getPriceSum() + $shipping_price;
							$total_shipping_amount += $shipping_price;
							
						?>
										@if($category_type == 'online_order')
												<tr>
													<td><span>{{$row->name}} </span></td>
													<td><?php echo $currency->symbol . ' ' . $row->price;?></td>
													<td>
														<div class="quantySelection">
															<div class="form-group">
																<div class="input-group">
																	<span class="input-group-btn">
																		<button type="button" class="btn btn-stock quantity-left-minus" data-type="minus" data-field="quant[2]">
																			<span class="glyphicon glyphicon-minus"></span>
																		</button>
																	</span>
																	<input type="number" value="<?php echo $row->quantity;?>" id="quantity_{{ $row->id }}" class="cls_quantity form-control input-number" data-row_id="{{ $row->id }}" data-stock="{{ $row->attributes['stock'] }}">
																	
																	<span class="input-group-btn">
																		<button type="button" class="btn btn-stock quantity-right-plus" data-type="plus" data-field="quant[2]">
																			<span class="glyphicon glyphicon-plus"></span>
																		</button>
																	</span>
																</div>
															</div>
														</div>												
													</td>

													<td class="delete-item">
														<span>
															<a href="{{ url('online-order/delete-cart') . '/' . $row->id}}" class="close"><i class="fa fa-trash-o" aria-hidden="true"></i>
														</span>
													</td>
													<td class="text-right">
														<span><?php echo $currency->symbol . ' ' . ($row->getPriceSum() + $shipping_price);?></span>
													</td>
												</tr>
											@endif	
								@if($category_type == 'online_shopping')
												<tr>
													<td><?php if($row->attributes['img']){?>
											<img src="{{url('')}}/upload/images/small/{{$row->attributes['img']}}"  class="tab-img"/><?php }?></td>

													<td>
														<div class="quantySelection">												
															<div class="form-group">
																<div class="input-group">
																	<span class="input-group-btn">
																		<button type="button" class="btn btn-stock" data-type="minus" data-field="quant[2]">
																			<span class="glyphicon glyphicon-plus"></span>
																		</button>
																	</span>
																	<input type="number" value="<?php echo $row->quantity;?>" id="quantity_{{ $row->id }}" class="cls_quantity form-control input-number" data-row_id="{{ $row->id }}" data-stock="{{ $row->attributes['stock'] }}">
																	<span class="input-group-btn">
																		<button type="button" class="btn btn-stock" data-type="plus" data-field="quant[2]">
																			<span class="glyphicon glyphicon-minus"></span>
																		</button>
																	</span>
																</div>
															</div>
														</div>
													</td>
													<td>
													@if($category_type == 'online_shopping')
														{{ $row->attributes['shipping_price'] }}
													@endif
													</td>
													<td class="delete-item">
														<span>
															<a href="{{ url('online-order/delete-cart') . '/' . $row->id}}" class="close"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
														</span>
													</td>
													<td class="text-right"><span><?php echo $currency->symbol . ' ' . ($row->getPriceSum() + $shipping_price);?></span>
													</td>
												</tr>
											@endif
												<?php } ?>
											</tbody>
											<tfoot>
											
											@if($category_type == 'online_order')												
												<tr >
												<td colspan="3" class="text-right">Delivery Fee</td>
												<td colspan="2" class="text-right"><?php echo $currency->symbol . ' '. $delivery_fee;?></td>
												</tr>
													<tr >
												<td colspan="3" class="text-right">&nbsp;</td>
												<td colspan="2" class="text-right">&nbsp;</td>
												</tr>
											@endif
												<tr>
													<td colspan="3" class="text-right"><strong>Total amount:</strong></td>
													<td colspan="2" class="text-right"><strong><?php echo $currency->symbol . ' '. $listing_total;
													$val=array('title' => $listing->title, 'amount' =>($currency->symbol.$listing_total));												
													array_push($summary_arr, $val);
													
													?></strong></td>
												</tr>										 
											</tfoot>
										</table>
									</div>
									
									
									
								</div>							
							</div>
							
							
							

						</div>
						
						
						<?php 
					$i++;
					endforeach;?>
						
						
						
						

					</div>
					
					
					
										
					
				
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-md-12 col-xs-12 pull-right text-center">
								<div class="demo-content">
									<h4 class="title-text">Price Details</h4>
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="price-table">
												<thead>

													<th></th>
													<th></th>

												</thead>
												<tbody>
										<?php	foreach($summary_arr as $key => $val){?>	
													<tr>
														<td class="text-left"><?php echo $val['title']; ?>	</td>
														<td class="text-right" style="vertical-align: baseline;">
														<?php echo $val['amount']; ?>															
														</td>

													</tr>
										<?php } ?>	

												</tbody>
												<tfoot>
													<tr>
														<td class="text-left"><strong>Total amount</strong></td>
														<td class="text-right"><strong><?php 
						$subtotal = \Cart::getSubTotal();
						$total = \Cart::getTotal() + $total_shipping_amount;
						
						 echo $currency->symbol.$total;?>
						</strong></td>
													</tr>										 
												</tfoot>
											</table>
										</div>
									</div>

								</div>
								<div class="row mt10">
									<div class="col-md-12 text-right proceedPay" style="margin-bottom: 10px;"> 
									<a href="{{ url('online-order/checkout') }}" class="btn btn-primary green">CHECKOUT</a> 
					<a href="{{ url('online-order/empty-cart') }}" class="btn btn-primary green">EMPTY CART</a>
									
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<?php
				
					}
					?>	
				<?php 
			} ?>
				</div>


				
			</div>
		</section>


		<!-- content-tab end -->

	</div>







@stop
								
@section('footer')	
<script>

$(document).ready(function(){
	
		var quantitiy=0;
   $('.quantity-right-plus').click(function(e){
      
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($(this).closest('.input-group').find('.cls_quantity').val());
        
        // If is not undefined
             // Increment
            $(this).closest('.input-group').find('.cls_quantity').val(quantity + 1);
          
         
        
    });

     $('.quantity-left-minus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($(this).closest('.input-group').find('.cls_quantity').val());
        
        // If is not undefined
      
            // Increment
            if(quantity>0){
            $(this).closest('.input-group').find('.cls_quantity').val(quantity - 1);
			
            }
    }); 
});
	$('.updcart').on('change keyup', function(){

		var newqty = $(this).val();
		var proId = $(this).data('id');

		if(newqty <=0){ alert('enter only valid qty') }
			else {

				$.ajax({
					type: 'get',
					dataType: 'html',
					url: '<?php echo url('/cart/update');?>/'+proId,
					data: "qty=" + newqty + "& proId=" + proId,
					success: function (response) {
						
						location.reload();
					}
				});
			}

		});
	$(document).on("change",".cls_quantity", add_cart_by_change);
	$(document).on("click",".quantity-right-plus", add_cart_by_change_btn);
	$(document).on("click",".quantity-left-minus", add_cart_by_change_btn);
	function add_cart_by_change_btn()
	{
		var submit_url = "{{ url('online-order/update-cart') }}";
		
		//event.preventDefault();
		$id = $(this).closest('.input-group').find('.cls_quantity').data('row_id');
		$stock = $(this).closest('.input-group').find('.cls_quantity').data('stock');	
		$quantity = $(this).closest('.input-group').find('.cls_quantity').val();
							
		if($quantity == '') {
			alert('Please enter quantity');
			return false;
		} else if($quantity <= 0) {
			alert('Quantity should be greater than or equal to 1');
			return false;
		}
		
		ajax_update_cart(submit_url, $id, $stock, $quantity);

	}
	
	function add_cart_by_change()
	{
		var submit_url = "{{ url('online-order/update-cart') }}";
		
		//event.preventDefault();
		$id = $(this).data('row_id');
		$stock = $(this).data('stock');	
		$quantity = $(this).val();
							
		if($quantity == '') {
			alert('Please enter quantity');
			return false;
		} else if($quantity <= 0) {
			alert('Quantity should be greater than or equal to 1');
			return false;
		}
		
		ajax_update_cart(submit_url, $id, $stock, $quantity);

	}
	
	function ajax_update_cart(submit_url, $id, $stock, $quantity)
	{
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		$.ajax(
		{
			url: submit_url,
			type: 'POST',
			data: {add_item: 'cart_page', row_id: $id, quantity: $quantity, stock: $stock, '_token':CSRF_TOKEN},
			dataType: "json",
			success:function(response)
			{
				//response = $.parseJSON( response );
				if(response.status) {
					
					window.location.reload();
					
				} else {
					alert(response.msg);
				}
			}
		});
	}
</script>
@stop





