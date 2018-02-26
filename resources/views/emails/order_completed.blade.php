@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Please find your order details below:</p>
	
	<table style="width:600px;">

		<tr>
			<th>Order Id</th>
			<th>Order Date</th>
		</tr>
		<tr>
			<td>{{ $main_order->invoice_id }}</td>
			<td>{{ $main_order->created_at }}</td>
		</tr>

		<tr>
			<th>Total Amount</th>
			<th>Order Status</th>
		</tr>
		<tr>
			<td>{{ $OrderBooking->total_amount}}</td>
			<td>Confirmed</td>
		</tr>

		<tr>
			<th>Email</th>
			<th>Phone No</th>
		</tr>
		<tr>
			<td>{{ $OrderBooking->email }}</td>
			<td>{{ $OrderBooking->phone_number }}</td>
		</tr>
		<tr>
			<th colspan="2">Delivery Address</th>
		</tr>
		<tr>
			<td colspan="2">
				{{ $OrderBooking->billing_detail->s_name }}<br />
				{{ $OrderBooking->billing_detail->s_address_1 }}
				@if($OrderBooking->billing_detail->s_address_2)
				<br />{{ $OrderBooking->billing_detail->s_address_2 }}
				@endif
				<br />
				{{ $OrderBooking->billing_detail->delivery_city->name }}<br />
				{{ $OrderBooking->billing_detail->delivery_state->name }}<br />
				{{ $OrderBooking->billing_detail->delivery_country->name }}
			</td>
		</tr>
	</table>
	
    <h3>Order Detail</h3>
	@foreach($merchant_orders as $merchant_order)
		<?php
			$order_detail = $merchant_order->order_details;
			$listing = $merchant_order->listing;
			$order_type = $merchant_order->order_type;
		?>
		<table class="table table-striped table-bordered bulk_action">
			<tr>
				<td>Listing Name</td>
				<td>Item Name</td>
				<td>Quantity</td>
				<td>Unit Price</td>
				@if($order_type == 'online_shopping')
					<td>Shipping Price</td>
				@endif
				<td>Total Amount</td>
			</tr>						
			@if(sizeof($order_detail)>0)
			@foreach($order_detail as $detail)
				<?php
				if($order_type == 'online_order') {
					$item_name = $detail->menu_item->name;
					$shipping_price = 0;
				} else {
					$item_name = $detail->shop_item->pro_name;
					$shipping_price = $detail->shop_item->shipping_price;
				}
				?>
			<tr>
				<td>{{ $listing->title }}</td>
				<td>{{ $item_name }}</td>
				<td>{{ $detail->quantity }}</td>
				<td>{{ $detail->unit_price }}</td>
				@if($order_type == 'online_shopping')
					<td>{{ $shipping_price }}</td>
				@endif
				<td>{{ $detail->total_amount + $shipping_price }}</td>
			</tr>
			@endforeach
			@endif
			@if($order_type == 'online_order')
			<tr>
				<td colspan="@if($order_type == 'online_shopping') 6 @else 5 @endif" align="right">Delivery Fee</td>
				<td>{{$merchant_order->total_shipping_amount}}</td>
			</tr>
			@endif
			<tr>
				<td colspan="@if($order_type == 'online_shopping') 6 @else 5 @endif" align="right"><b>Order Total</b></td>
				<td>{{$merchant_order->total_amount}}</td>
			</tr>
		</table>
	@endforeach
	
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')