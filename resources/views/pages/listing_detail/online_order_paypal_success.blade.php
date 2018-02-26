@extends('layouts.main')
@section('content')
@include('partials.status-panel')

<!-- Main Section -->
<div class="main-section" style="min-height:400px;">
	<div class="container">
		<div class="row">		
			<div class="col-md-8 col-md-offset-2 m-t50">
				<ul class="shop-progress">
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
			<div class="col-sm-12 m-t50 m-b50 text-center">
				<h2>Dear Member</h2>
				<p>Your payment was successful, thank you for purchase.</p><br/>
				<p>Order Number : 
					<strong><?php echo $order_number; ?></strong>
				</p>
				<p>TXN ID : 
					<strong><?php echo $txn_id; ?></strong>
				</p>
				<p>Amount Paid : 
					<strong><?php echo $currency_code.' '.$payment_amt; ?></strong>
				</p>
				<p>Payment Status : 
					<strong><?php echo $status; ?></strong>
				</p>
			</div>
		</div>
	</div>
</div>
@stop