@include('emails.header')

    <h2>Dear {{$name}}</h2>
	<p>Congratulation!</p>
    <p>You have earned {{$earn_points}} points by applying successfull refarral code.</p>
	<p>Thank You.</p>	
	<p>
	The Team,<br />
	{{$site_name}}
	</p>

@include('emails.footer')