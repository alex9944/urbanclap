@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Please find your booking details below:</p>
    <table>
		<tr>
			<td>Order Id</td>
			<td>{{$main_order->invoice_id}}</td>
		</tr>
		<tr>
			<td>Listing Name</td>
			<td>{{$listing_name}}</td>
		</tr>
		<tr>
			<td>Order Date</td>
			<td>{{$main_order->created_at}}</td>
		</tr>
		<tr>
			<td>Booking Date</td>
			<td>{{$TableBookingOrder->booked_date}}</td>
		</tr>
		<tr>
			<td>Booking Time</td>
			<td>{{$TableBookingOrder->booked_time}}</td>
		</tr>
		<tr>
			<td>Total Peoples</td>
			<td>{{$TableBookingOrder->total_peoples}}</td>
		</tr>
	</table>
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')