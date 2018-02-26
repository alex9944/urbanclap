@extends('layouts.'.$merchant_layout)

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/subcription_plan.css') }}">
@stop

@section('content')
	
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	
	@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
	
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	<form name="subscriptionForm" action="{{url('merchant/change_subscription_plan')}}" id="subscriptionForm" method="post" role="form">
		<div class="col-md-12 col-xs-12">
			<table class="table">
				<tr>
					<td>Plan</td>
					<td>
					<?php
					$feature_list = '';
					$sep = '';
					foreach($subscription_features as $subscription_feature) 
					{
						if(!empty($category_types) and $subscription_feature->functionality_name == 'category_dependent')
						{
							foreach($category_types as $category_type) {
								$feature_list .= $sep . $category_type->name;
								$sep = ', ';
							}
						}
						else {
							$feature_list .= $sep . $subscription_feature->title;
							$sep = ', ';
						}
					}
					echo $subscription_pricing->plan->title . ' - ' . $feature_list;
					?>
					</td>
				</tr>
				<tr>
					<td>Duration</td>
					<td>{{$subscription_term->display_value}}</td>
				</tr>
				<tr>
					<td>Payable Amount</td>
					<td>{{ $currency->symbol . $paid_amount}}</td>
				</tr>
				<?php /*<tr>
					<td>Payment Gateway</td>
					<td>{{ $currency->symbol . $paid_amount}}</td>
				</tr>*/?>
				<tr>
					<td>Effective From</td>
					<td><?php echo date('F d,Y', strtotime($subscribed_date));?></td>
				</tr>
				<tr>
					<td>Effective To</td>
					<td><?php echo date('F d,Y', strtotime($expired_date));?></td>
				</tr>
			</table>
			
			<input type="hidden" name="payment_gateway_id" value="{{$request->payment_gateway_id}}" >
			<input type="hidden" name="subscription_term_id" value="{{$request->subscription_term_id}}" >
			<input type="hidden" name="subscription_pricing_id" value="{{$request->subscription_pricing_id}}">
			<input type="hidden" name="subscribed_date" value="{{$subscribed_date}}">
			
			<div style="display: ruby-text;"><button type="submit" class=" btn btn-cus save-btn">Confirm</button> </div>
			
			<div><button type="button" class="btn btn-cus save-btn" onclick="window.location='{{url('merchant/change-subscription-plan')}}';">Cancel</button></div><br><br>

		</div>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
    </form>    

@stop	