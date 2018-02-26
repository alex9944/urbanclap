@extends('layouts.main')
@section('content')
@include('partials.status-panel')

<!-- Main Section -->
<div class="main-section">
	<div class="container">
	
		<div class="row">			
			@if($response_html)
			<div class="title-box" style=" margin:50px 0 50px 0;">
				<h1 class="text-center text-uppercase title">Thank you</h1>
				<p>Your order received.</p>
			</div>
				
							
			<div class="inner" style="min-height:300px;">
				<h2 class="text-center">Details:</h2>
				<?php echo $response_html;?>
				
			</div>
			@else
				<div class="inner text-center" style="min-height:300px; margin:50px 0 0 0;">
					<h2> Invalid order</h2>
				</div>
			@endif
		</div>
		
	</div>
</div>
@stop