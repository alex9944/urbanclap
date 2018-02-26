@extends('layouts.'.$merchant_layout)

@section('content')
@include('partials.status-panel')

<!-- Main Section -->
<div class="main-section">
	<div class="container">
		<div class="row">
			<!-- title -->
			<div class="title-box" style=" margin:50px 0 20px 0;">
				<h1 class="text-center title">Processing Payment</h1>
			</div>
							
			<div class="inner" style="min-height:300px;text-align: center;">				
				
				<button id="razorpay-payment-button">Click here if you're not automatically redirected...</button>
				
				<form name="subscriptionForm" action="{{$razor_fields['return_url']}}" id="subscriptionForm" method="post" role="form">
					<input type="hidden" name="order_id" value="{{$razor_fields['order_id']}}" >
					<input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="" >
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				</form>
					
			</div>
		</div>
	</div>
</div>
@stop
								
@section('footer')	

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
var options = {
    "key": "{{$razor_fields['key']}}",
    "amount": "{{$razor_fields['amount']}}", // 1000 paise = INR 10
    "name": "Apoyou",
    "description": "{{$razor_fields['description']}}",
    "image": "{{url('assets/images/logo.png')}}",
    "handler": function (response){
        //alert(response.razorpay_payment_id);
		document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
		document.forms['subscriptionForm'].submit();
    },
	"modal": { 
		"ondismiss": function (){
			window.location = "{{url('merchant/subscription/complete/'.$encrypt_order_id)}}";
		}
	},
    "prefill": {
        "name": "{{$razor_fields['customer_name']}}",
        "email": "{{$razor_fields['customer_email']}}",
        "contact": "{{$razor_fields['phone']}}"
    },
    "notes": {
        "order_id": "{{$razor_fields['order_id']}}"
    },
    "theme": {
        "color": "#F37254"
    }
};
var rzp1 = new Razorpay(options);

document.getElementById('razorpay-payment-button').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}

$(document).ready(function() {
			
	$('#razorpay-payment-button').trigger('click');
	
});
</script>
@stop