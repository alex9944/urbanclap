@include('emails.header')

    <h2>Dear {{$name}}</h2>
    <p>Your order status updated to {{ $product_status }}.
	@if($status == 'cancelled')
		The amount of the corresponding item will be refunded shortly.
	@endif
	</p>
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')
