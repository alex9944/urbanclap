@extends('layouts.main')

@section('head')

@stop

@section('content')
<link href="{{URL::asset('assets/css/jquery.countdownTimer.css')}}" rel="stylesheet">
<script src="{{URL::asset('assets/js/jquery.countdownTimer.min.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
	.colorDefinition{
		background: #ffffff !important;
		color: #0a0a0a !important;
		border-color: #fffeff !important;
	}
	#verify,#resend{
		float: right;
		margin:5px;
	}

</style>

<div class="">
	<div class="page-title">


	</div>
	<div class="clearfix"></div>           

	<div class="row">
		<div class="col-md-12" >
			<div class="col-md-offset-4 col-md-6 col-md-12">
				<p id="hidelink"><strong>Click <a id="verify-link" style="color:red;"> here </a> to verify your account.</strong></p>
			</div>

		</div>
		<div class="col-md-12" id="verification" style="display:none">
			<div class="col-md-offset-4 col-md-6 col-md-12">
				<div class="col-md-12">
					<h3>ACCOUNT VERIFICATION</h3>
				</div>
				<div class="x-panel">
					<form id="otpverification" action="" method="POST">
						<div class="col-md-12">
							<div class="text-center alert alert-danger" id="invalid_otp_det" style="display:none;">

							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group col-md-11">
								<label class="col-sm-4 control-label">Enter Your OTP</label>
								<div class="col-md-6">
									<input class="form-control" type="password" name="otpcode" id="otpcode">
								</div>
							</div>
							<div class="col-md-1 txt-black" id="countdown"><span id="otp_timer"><span></div></div>
							<div class="form-group col-md-9">
								<button type="button" id="resend" class="btn btn-danger active">Resend</button>
								<button type="button" id="verify" class="btn btn-primary active">Verify</button>

							</div>
						</form>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-12">
						<div class="col-md-offset-4 col-md-6 col-md-12">
							<p><strong>Note: </strong>OTP has been sent to your Registered Mobile No</p>
						</div>
					</div>
				</div>
			</div>
		</div>

				<div class="clearfix"></div>  
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#otp_timer').show();
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var id={{Session::get('merchant_id')}};
				var otp_no='';
				function updateOTP(number,otp){
					$.ajax({
						type:"POST",
						url:"{{url('/merchant/updateOtp')}}",
						data:{'mobile':number,'otp':otp,'_token':CSRF_TOKEN},
						dataType:'json',
						async:false,
						success:function(data){
							$('#otp_timer').show();
							$('#otp_timer').countdowntimer({
								minutes :5,
								seconds:0,
								timeUp :timeIsUp
							});
						}
					});
				}
				function timeIsUp() {
					$('#otp_timer').show();
					$("#invalid_otp_det").html('');
					$('html,body').animate({
						scrollTop: 0},
						'slow');
					$("#invalid_otp_det").append('Time OUT');
					$("#invalid_otp_det").show();
					setTimeout(function(){$("#invalid_otp_det").fadeOut(); }, 2000);
					$.ajax({
						type:"POST",
						url:"{{url('/merchant/updateValid')}}",
						data:{'mobile':otp_no,'_token':CSRF_TOKEN},
						dataType:'json',
						success:function(data){
							//alert(data);
						}
					});
				}
				function sendotp(){
					$('#hidelink').hide();
					$.ajax({
						type:"POST",
						url:"{{url('/merchant/getmobile')}}",
						data:{'id':id,'_token':CSRF_TOKEN},
						async:false,
						dataType:'json',
						success:function(number){
							var otp='';
							otp_no=number;
							var possible = "0123456789";
							for( var i=0; i < 6; i++ )
								otp += possible.charAt(Math.floor(Math.random() * possible.length));
							$.ajax({
								type:"POST",
								url:"https://api.checkmobi.com/v1/sms/send",
								ContentType: "application/json",
								data:JSON.stringify({"to":'+91'+number, "text":"Your OTP for account Verification is "+otp, "platform":"web"}),
								dataType:'json',
								beforeSend: function(xhr){xhr.setRequestHeader('Authorization', '2B6133A9-764E-4918-91A9-B6C6B3181CB1');},
								success:function(data){
									updateOTP(number,otp);
									
								},
								error:function(data){
									//console.log(data);
								}

							});
						}
					});
				}
				$('#verify-link').click(function(){
					$('#verification').show();
					sendotp();
				});
				$('#verify').click(function(){
					var code=$('#otpcode').val();
					$.ajax({
						type:"POST",
						url:"{{url('/merchant/checkOtp')}}",
						data:{'id':id,'otp':code,'_token':CSRF_TOKEN},
						dataType:'json',
						success:function(data){
							$('#invalid_otp_det').html('');
							if(data==0){
								$('html,body').animate({
									scrollTop: 0},
									'slow');
								$('#invalid_otp_det').append('Invalid OTP!');
								$("#invalid_otp_det").show();
								setTimeout(function(){$("#invalid_otp_det").fadeOut(); }, 2000);
								$('#otpcode').val('');
							}
							else{
								window.location.href="{{url('/merchant/add-listing')}}";
							}
						}
					});
				});
				$('#resend').click(function(){
					sendotp();
				});
			});
		</script>
		@stop

