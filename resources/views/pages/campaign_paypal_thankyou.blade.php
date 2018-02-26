@extends('layouts.main')
@section('content')
@include('partials.status-panel')

<!-- Main Section -->
<div class="main-section">
	<div class="container">
		<div class="row">			
			<div class="col-md-8 col-md-offset-2">
				<ul class="shop-progress m-b70">
					<li>
						<span class="thumb-icon">01</span>
						<span class="title">SHOPPING CART</span>
					</li>
					<li>
						<span class="thumb-icon">02</span>
						<span class="title">CHECKOUT</span>
					</li>
					<li class="active">
						<span class="thumb-icon">03</span>
						<span class="title">ORDER COMPLATE</span>
					</li>
				</ul><!-- /.shop-progress -->
			</div>		
		</div>
		
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