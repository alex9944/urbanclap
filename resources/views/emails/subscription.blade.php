@include('emails.header')

    <h2 align="center">Thank you</h2>
    <p>Your order received. We will review your profile and after approval/disapproval you will be notified via mail. If approved then you can login and use our service otherwise we will refund your paid amount.</p>
	<div>
		<h4 align="center">Your order detail:</h4>
		<p>Order ID : {{$order_id}}</p>
		<p>Transaction ID : {{$payment_id}}</p>
		<p>Payment Status : {{$status}}</p>
	</div>
	<br /><br />
	<p>
	The Team,
	Needifo.
	</p>

@include('emails.footer')