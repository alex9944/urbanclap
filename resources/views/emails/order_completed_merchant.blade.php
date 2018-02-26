@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Please find the order details below:</p>
	
	<table class="table table-striped table-bordered bulk_action">

		<tr>
			<th>Order Id</th>
			<th>Order Date</th>
		</tr>
		<tr>
			<td>{{ $merchant_order->invoice_id }}</td>
			<td>{{ $merchant_order->created_at }}</td>
		</tr>

		<tr>
			<th>Total Amount</th>
			<th>Customer Name</th>
		</tr>
		<tr>
			<td>{{ $merchant_order->total}}</td>
			<td>{{ $OrderBooking->name }}</td>
		</tr>

		<tr>
			<th>Customer Email</th>
			<th>Customer Phone No</th>
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
	
    <h3>Item Detail</h3>
	<table class="table table-striped table-bordered bulk_action">
		<tr>
			<td>Item Name</td>
			<td>Status</td>
			<td>Quantity</td>
			<td>Unit Price</td>
			<td>Total Amount</td>
		</tr>
	@php
		$order_detail = $merchant_order->order_details;
		$listing = $merchant_order->listing;
		$order_type = $merchant_order->order_type;
	@endphp
	
	@if(sizeof($order_detail)>0)
	@foreach($order_detail as $detail)
		<?php
		if($order_type == 'online_order') {
			$item_name = $detail->menu_item->name;
		} else {
			$item_name = $detail->shop_item->pro_name;
		}
		?>
	<tr>
		<td>{{ $item_name }}</td>
		<td>
			@if($detail->status == 'cancelled' || $detail->status == 'completed')
				{{$order_detail_status[$detail->status]['name']}}
			@else
				<select name="status[{{$detail->id}}]" id="status"  class="form-control">
					@foreach($order_detail_status as $status_arr)
					<option value="{{ $status_arr['id'] }}" @if( old('status', $detail->status) == $status_arr['id'] ) selected @endif>
						{{ $status_arr['name'] }}
					</option>
					@endforeach
				</select>
			@endif
		</td>
		<td>{{ $detail->quantity }}</td>
		<td>{{ $detail->unit_price }}</td>
		<td>{{ $detail->total_amount }}</td>
	</tr>
	@endforeach
	@endif
	@if($merchant_order->total_shipping_amount)
	<tr>
		<td colspan="4" align="right">Delivery Fee</td>
		<td>{{$merchant_order->total_shipping_amount}}</td>
	</tr>
	@endif
	<tr>
		<td colspan="4" align="right"><b>Order Total</b></td>
		<td>{{$merchant_order->total_amount}}</td>
	</tr>
	</table>
	
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')