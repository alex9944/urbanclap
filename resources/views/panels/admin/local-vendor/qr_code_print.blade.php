<div style="margin:50px 0 0 50px; text-align:center;">
	<div style="margin:0 0 20px 0; font-size:34px;">
		Unique Code: {{$marketing_user->unique_code}}
	</div>
	<div>
		{!! QrCode::size(400)->generate($marketing_user->unique_code); !!}
	</div>
</div>