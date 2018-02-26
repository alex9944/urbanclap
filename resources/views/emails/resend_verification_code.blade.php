@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Please use the following verification code to login apoyou.com.</p>
    <p>Verification Code : {{$verification_code}}</p>
	<p>Thank You.</p>
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')
