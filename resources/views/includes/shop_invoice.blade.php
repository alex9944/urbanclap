<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php
	$site_name = ($site_settings->site_name != '') ? $site_settings->site_name : 'Apoyou';
	$title = $site_name;
	?>
    <title>{{$title}} Invoice</title>
    
    <link href="{{ asset($merchant_layout . '/css/bootstrap.min.css') }}" rel="stylesheet">
    <style type="text/css">
    .invoice-title h2, .invoice-title h3 {
		display: inline-block;
	}

	.table > tbody > tr > .no-line {
		border-top: none;
	}

	.table > thead > tr > .no-line {
		border-bottom: none;
	}

	.table > tbody > tr > .thick-line {
		border-top: 2px solid;
	}
    </style>
    <script src="{{ asset($merchant_layout . '/js/jquery.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/bootstrap.min.js') }}"></script>
    
</head>
<body>
	<div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2><img src="{{url('')}}/assets/images/logo.png" style="width: 200px;"/></h2>
				<div class="pull-right">
					<h3>Order # {{ $order->main_order->main_order->invoice_id }}</h3> <br>
					<h4>{{ $order->listing->title }}</h4>
				</div>
    		</div>
    		<hr />    		
    	</div>
    </div>
	
	<div class="row">
		<div class="col-xs-6">
			<address>
			<strong>Delivery Address:</strong><br>
				{{ $order->main_order->name }}<br>
				{{$order->main_order->billing_detail->s_address_1}}
				@if($order->main_order->billing_detail->s_address_2)
				<br>
				{{$order->main_order->billing_detail->s_address_2}}<br>
				@endif
				{{$order->main_order->billing_detail->delivery_city->name}}<br>
				Ph: {{ $order->main_order->phone_number }}
			</address>
		</div>
		<div class="col-xs-5 text-right">
			<address>
				<strong>Order Date:</strong><br>
				<?php echo date('F d, Y', strtotime($order->main_order->created_at));?><br><br>
			</address>
		</div>
	</div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Item</strong></td>
        							<td class="text-center"><strong>Price</strong></td>
        							<td class="text-center"><strong>Quantity</strong></td>
									<td class="text-center"><strong>Shipping</strong></td>
        							<td class="text-right"><strong>Total</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<?php
									$order_detail = $order->order_details;
								?>
								<!-- foreach ($order->lineItems as $line) or some such thing here -->
    							@foreach($order_detail as $detail)
									<?php
									$item_name = @$detail->shop_item->pro_name;
									$shipping_price = @$detail->shop_item->shipping_price;
									$total_amount = isset($detail->total_amount) ? $detail->total_amount : 0;
									?>
								<tr>
    								<td>{{$item_name}}</td>
    								<td class="text-center"><?php echo $currency->code;?> {{$detail->unit_price}}</td>
    								<td class="text-center">{{$detail->quantity}}</td>
									<td class="text-center">@if($shipping_price)<?php echo $currency->code;?> @endif{{$shipping_price}}</td>
    								<td class="text-right"><?php echo $currency->code;?> {{$total_amount+$shipping_price}}</td>
    							</tr>
                                @endforeach
    							
								<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong>Subtotal</strong></td>
    								<td class="thick-line text-right"><?php echo $currency->code;?> {{$order->sub_total}}</td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Shipping</strong></td>
    								<td class="no-line text-right">@if($order->total_shipping_amount)<?php echo $currency->code;?> @endif{{$order->total_shipping_amount}}</td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Total</strong></td>
    								<td class="no-line text-right"><?php echo $currency->code;?> {{$order->total_amount}}</td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
</body>
</html>