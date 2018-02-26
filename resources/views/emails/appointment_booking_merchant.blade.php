@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Please find the user details below:</p>
    <table>
		<tr>
			<td>Order Id</td>
			<td>{{$main_order->invoice_id}}</td>
		</tr>
		<tr>
			<td>Order Date</td>
			<td>{{$main_order->created_at}}</td>
		</tr>
		<tr>
			<td>Booking Date</td>
			<td>{{$AppointmentBookingOrder->booked_date}}</td>
		</tr>
		<tr>
			<td>Booking Time</td>
			<td>{{$AppointmentBookingOrder->booked_time}}</td>
		</tr>
		<tr>
			<td>Customer Name</td>
			<td>{{$AppointmentBookingOrder->name}}</td>
		</tr>
		<tr>
			<td>Customer Email</td>
			<td>{{$AppointmentBookingOrder->email}}</td>
		</tr>
		<tr>
			<td>Customer Phone</td>
			<td>{{$AppointmentBookingOrder->phone_number}}</td>
		</tr>
		<tr>
			<td>Additional Request</td>
			<td>{{$AppointmentBookingOrder->additional_request}}</td>
		</tr>
	</table>
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')