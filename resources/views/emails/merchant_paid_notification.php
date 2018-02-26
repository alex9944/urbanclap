@include('emails.header')

    <h2>Dear {{$name}}</h2>
    <p>{{$site_name} paid your net amount of purchase.</p><br />
	<h4>Payment Detail</h4>
	<p>Paid Amount : {{$paid_amount}}</p><br />
	@if($cheque_no))
	<p>Cheque No : {{$cheque_no}}</p><br />
	@endif
	<p>Thank You.</p>
	<br /><br />
	<p>
	The Team,
	{{$site_name}}
	</p>

@include('emails.footer')
