@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Please use the following verification code to complete the signup progress.</p>
    <p>Verification Code : {{$verification_code}}</p>
	<p>Thank You.</p>
	<br />
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')
