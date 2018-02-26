@extends('layouts.'.$merchant_layout)
@section('content')
@include('partials.status-panel')

<!-- Main Section -->
<div class="main-section">
	<div class="container">		
		<div class="row">			
			<!-- title -->
			@if($order)
				<div class="title-box" style=" margin:0 0 50px 0;">
					<h1 class="text-center text-uppercase title">Thank you</h1>
				</div>
				<div class="inner text-center" style="min-height:300px;">
					<h2>Your order detail:</h2>
					<p>Order Number : 
						<strong><?php echo $order->id; ?></strong>
					</p><br/>
					<p>Total Items : 
						<strong><?php echo $order->total_items; ?></strong>
					</p><br/>
					<p>Total Amount : 
						<strong><?php echo $order->total_amount.' '.$currency->code; ?></strong>
					</p><br/>
				</div>
			@else
				<div class="inner text-center" style="min-height:300px;">
					<h2> Invalid order</h2>
				</div>
			@endif
			
		</div>
	</div>
</div>
@stop