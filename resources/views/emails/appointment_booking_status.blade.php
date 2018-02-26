@include('emails.header')

    <h2>Dear {{$name}}</h2>
	
	@if($TableBookingOrder->status == 1)
		<p>Your recent appointment booking request on {{$listing_name}} for the Order Id {{$main_order->invoice_id}} has been CONFIRMED.</p>
	
	@elseif($TableBookingOrder->status == 2)
		<p>Your recent appointment booking request on {{$listing_name}} for the Order Id {{$main_order->invoice_id}} has been Declined. Sorry for the inconvenience.</p>
	
	@endif
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')