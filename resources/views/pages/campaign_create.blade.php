@extends('layouts.main')
@section('content')
@include('partials.status-panel')
<style>
ul.shop-progress li{
	margin-right: 0% !important;
	padding: 5px;
	width: 25%;
}
.nav-tabs li a, .nav-tabs li a:hover {
	background: none !important; 
	border-bottom: none !important; 
	border-left:none !important; 
	border-right: none !important; 
	border-top: none !important; 
	color: #CCC;
}

</style>
<link rel="stylesheet" href="{{url('')}}/assets/css/campaign.css">
<script type="text/javascript" src="{{url('')}}/assets/js/scripts.js"></script>
<script type="text/javascript" src="{{url('')}}/assets/js/jquery.validate.js"></script>
<div class="container">
	<div class="row">
		<section>
			<div class="col-md-10 col-md-offset-1">
				<div class="wizard">
					<div class="wizard-inner">

						<ul class="shop-progress nav nav-tabs" role="tablist">
							<li class="active">
								<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="" data-original-title="Step 1"  class="nostyle">
									<span class="thumb-icon">01</span>
									<span class="title">SHOPPING </span>
								</a>
							</li>
							<li>
								<a href="#step2" data-toggle="tab" aria-controls="step1" role="tab" title="" data-original-title="Step 2">
									<span class="thumb-icon">02</span>
									<span class="title">ORDER</span>
								</a>
							</li>
							<li>
								<a href="#step3" data-toggle="tab" aria-controls="step1" role="tab" title="" data-original-title="Step 3">
									<span class="thumb-icon">03</span>
									<span class="title">CHECKOUT </span>
								</a>
							</li>
							<li>
								<a href="#complete" data-toggle="tab" aria-controls="step1" role="tab" title="" data-original-title="Complete">
									<span class="thumb-icon">04</span>
									<span class="title">COMPLETE</span>
								</a>
							</li>
						</ul>

					</div>

					<div class="tab-content">
						<div class="tab-pane active" role="tabpanel" id="step1">
							<div class="row">
								<div class="col-md-12">
									<div class="box" style="height: 200px;">
										<div class="row">
											<div class="col-md-12">
												<div class="heading">
													<h2>Choose Category Options</h2>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="">
													<div class="col-md-6">
														<div class="">
															<div class="cf">
																<h3>Select Category</h3>
															</div>
															<div class="">
																<select name="category" id="category" class="form-control">
																	@foreach($category as $category)
																	<option value="{{$category->c_id}}">{{$category->c_title}} </option>
																	@endforeach
																</select>
															</div>

														</div>
													</div>
													<div class="col-md-6">
														<div class="">
															<div class="cf">
																<h3>Select Subcategory</h3>
															</div>
															<div class="">
																<select name="s_category" id="s_category" class="form-control">

																</select>
															</div>

														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-12">
									<div class="box  ">
										<div class="row">
											<div class="col-md-12">
												<div class="heading">
													<h2>Choose Targeting and Scheduling Options</h2>
													<p>Specify your targeting and scheduling options below (optional).</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="">
													<div class="col-md-4">
														<div class="">
															<div class="cf">
																<h3>Select State</h3>
															</div>
															<div class="box-content">
																<ul class="selectitems">
																	@foreach($states as $state)
																	<li>
																		<a class="state" style="cursor: pointer;" data-state="{{$state->id}}">
																			{{$state->name}}
																			<i class="fa fa-arrow-right"></i>
																		</a>
																	</li>
																	@endforeach
																</ul>
															</div>

														</div>
													</div>
													<div class="col-md-4">
														<div class="cf">
															<h3>Select City</h3>
														</div>
														<div class="box-content">
															<ul class="selectitems city">
																<li>
																	<a  style="cursor: pointer;">
																		Choose state
																		<i class="fa"></i>
																	</a>
																</li>
															</ul>
														</div>

													</div>
													<div class="col-md-4">
														<div class="cf">
															<h3>Select Zone</h3>
														</div>
														<div class="box-content">
															<ul class="selectitems">
																<li>
																	<a class="zone" style="cursor: pointer;" data-zone="north">
																		North
																		<i class="fa fa-arrow-right"></i>
																	</a>
																</li>
																<li>
																	<a class="zone" style="cursor: pointer;" data-zone="south">
																		South
																		<i class="fa fa-arrow-right"></i>
																	</a>
																</li>
																<li>
																	<a class="zone" style="cursor: pointer;" data-zone="east">
																		East
																		<i class="fa fa-arrow-right"></i>
																	</a>
																</li>
																<li>
																	<a class="zone" style="cursor: pointer;" data-zone="west">
																		West
																		<i class="fa fa-arrow-right"></i>
																	</a>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-md-12">
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
                     <!--                        <div class="col-md-12">
                                                                <div class="order-total">
	<div class="total">
		Total: <em>$<span class="amount">150.00</span></em>
	</div>

			<div class="separator cf"></div>
		<div id="discountshow"><a>Have a discount code?</a></div>
		
			


			
		</div> 
	</div> -->
	<div class="col-md-12 mt20">


		<section class="section section--large section--pastel">


			<section class="ad-inventory">
				<div class="container">
					<div class="row main-row">
						<div class="three columns">
							<div class="controls">
								<div class="info">
									<h1 class="info__title">Banners</h1>
									<?php $positions=json_decode(json_encode($position));
									?>
									@foreach($positions as $value)
									@if($value->pos_id!="Interstials")
									<button class="info__button" id="button{{$value->pos_id}}" data-banner="{{$value->id}}">{{$value->title}}</button>
									@endif
									@endforeach
								</div>
							</div>
							<div class="controls">
								<div class="info">
									<h1 class="info__title">Pops</h1>
                     <!--  <button class="info__button" id="button-pop-under">pop-under</button>
                      <button class="info__button" id="button-pop-up">pop-up</button>
                      <button class="info__button" id="button-pop-tab">pop-tab</button> -->
                      @foreach($positions as $value)
                      @if($value->pos_id=="Interstials")
                      <button class="info__button" id="button-pop-interstitial" data-banner="{{$value->id}}">{{$value->title}}</button>
                      @endif
                      @endforeach
                      
                  </div>
              </div>
          </div>
          <div class="six columns align-center">
          	<div class="desktop visible" id="desktop">
          		<img class="desktop__frame" src="{{url('')}}/assets/images/Ad/desktop.png" role="presentation">
          		<div class="desktop__content">
          			<img class="desktop__body" src="{{url('')}}/assets/images/Ad/desktop-body.png" alt="Desktop Webpage">
          			@foreach($positions as $value)
          			<img class="desktop__banner" id="desktop-{{$value->pos_id}}" src="{{url('')}}/assets/images/Ad/{{$value->d_image}}" alt="Desktop Banner 300 ad">
          			@endforeach
          		</div>
          	</div>

          	<div class="mobile" id="mobile">
          		<div class="mobile__frame">
          			<img id="mobile-banners" src="{{url('')}}/assets/images/Ad/phone.png" role="presentation">
          			<img id="mobile-pops" src="{{url('')}}/assets/images/Ad/phone-pops.png" role="presentation">
          		</div>

          		<div class="mobile__content">
          			<img class="mobile__body" src="{{url('')}}/assets/images/Ad/phone-body.png" alt="Mobile Webpage">
          			@foreach($positions as $value)
          			<img src="{{url('')}}/assets/images/Ad/{{$value->m_image}}" alt="Moible Banner 320" id="mobile-{{$value->pos_id}}" class="mobile__banner">
          			@endforeach
          		</div>
          	</div>

          	<div class="container">
          		<div class="twelve columns">
          			<div class="info">
          				<h1 class="info__title">Mode</h1>
          				<button class="info__button selected mode" id="desktop-button">desktop</button>
          				<button class="info__button mode" id="mobile-button">mobile</button>
          			</div>
          		</div>
          	</div>
          	<div class="col-md-12">
          		<div class="order-total">
          			<div class="total">
          				Total: <em><label class="amount" style="font-size: 25px;margin-top:10px"></label></em>
          			</div>
          			<input type="hidden" name="price" id="price">
          			<div class="separator cf"></div>
          			<!-- <div id="discountshow"><a>Have a discount code?</a></div> -->
          		</div> 	
          	</div>
          </div>

      </div>
  </div>
</section>

</section>


</div>
</div>




<ul class="list-inline pull-right">
	<li>
		<button type="button" class="btn btn-default save" >Save and continue</button>
	</li>
</ul>
</div>

<div class="tab-pane " role="tabpanel" id="step2">
	<form role="form" id="campaign_form" method="post" enctype='multipart/form-data'>
		<div class="row">
			<div class="col-md-12">
				<div class="box  ">
					<div class="row">
						<div class="col-md-12">
							<!-- <div class="heading">
								
							</div> -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Alternate Text</label>
								<input type="text" class="form-control" id="company" name="company" placeholder="Your Company Name" required>
								<span class="fafter">This is what users will see in some browsers when their mouse hovers over the ad.</span>
								<input type="hidden" name="_token" value="{{csrf_token()}}">
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Destination URL</label>
								<input type="text" class="form-control" id="url" name="url" placeholder="http://example.com/" required>
							</div>
							<div class="form-group">
								<label for="exampleInputFile">Image</label>
								<input type="file" id="file" name="ad_image" class="form-control" required>
								<span class="fafter filevalid">(Allowed: JPG, PNG, GIF, 300x250, max 150 KB)</span>
							</div>
							<input type="hidden" id="campaign_id" name="campaign_id">
							<input type="hidden" id="height" name="height">
							<input type="hidden" id="width" name="width">
						</div>
					</div>
				</div>
			</div>
		</div>

		<ul class="list-inline pull-right">
			<li>
				<button type="submit" class="btn btn-default save-form">Save and continue</button>
			</li>
		</ul>
	</form>
</div>

<div class="tab-pane" role="tabpanel" id="step3">
	<form id="checkout_form" name="checkout_form" action="{{url('')}}/campaign/checkout" method="post">
		<div class="box">
			<div class="heading mb25">
				<h2>Ur Details</h2>
				<p>Specify your targeting and scheduling options below (optional).</p>
			</div>
			<div class="col-md-12 ">


				<div class="col-md-6">
					<div class="form-group">
						<label class="col-lg-4 control-label">State</label>
						<div class="col-lg-8">
							<label class=" control-label" id="cur_state">State</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 control-label">City</label>
						<div class="col-lg-8">
							<label class=" control-label" id="cur_city">City</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 control-label">Zone</label>
						<div class="col-lg-8">
							<label class=" control-label" id="cur_zone">Zone</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 control-label">Template</label>
						<div class="col-lg-8">
							<img src="" id="template_img">
						</div>
					</div>
					<input type="hidden" name="campaign_id" id="camp_id">
					<input type="hidden" name="price" id="price">
					<input type="hidden" name="_token" value="{{ csrf_field() }}">
				</div>
				<div class="col-md-6"></div>
			</div>



			<div class="col-md-12">
				<div class="order-total">
					<div class="total">
						Total: <em><label class="amount"></label></em>
					</div>
					
					<div class="separator cf"></div>
					<!-- <div id="discountshow"><a>Have a discount code?</a></div> -->





				</div> 	
			</div>
			<?php
			$payment_gateways = App\Models\PaymentGatewaySiteSettings::get();
			?>
			<div class="payment-method">
				<h6 class="heading2">PAYMENT METHOD</h6>
				<ul>
					@foreach($payment_gateways as $gateway)
					<li>
						<div class="media-left">
							<input type="radio" name="payment_gateway_id" value="{{$gateway->payment_gateway->id}}" >
						</div>
						<div class="media-body">
							<h6 class="title">{{$gateway->payment_gateway->name}}</h6>
						</div>
					</li>
					@endforeach
				</ul>
			</div>
		</div>

		<ul class="list-inline pull-right">
			<li>
				<button type="submit" class="btn btn-default next-step">Save and continue</button>
			</li>
		</ul>
	</form>
</div>
<div class="tab-pane" role="tabpanel" id="complete">

	<p>You have successfully completed .</p>
</div>
<div class="clearfix"></div>
</div></form>

</div>
</div>
</section>
</div>
</div>
<script>
	$(document).ready(function() {
		var campaign_arr={};
		campaign_arr['mode']='desktop';
            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

            	var $target = $(e.target);

            	if ($target.parent().hasClass('disabled')) {
            		return false;
            	}
            });

            $(".next-step").click(function(e) {

            	var $active = $('.wizard .nav-tabs li.active');
            	$active.next().removeClass('disabled');
            	nextTab($active);

            });
            $(".prev-step").click(function(e) {

            	var $active = $('.wizard .nav-tabs li.active');
            	prevTab($active);

            });
            //Get Subcategories

            $(document).on("change",'#category',getsubcategories);
            function getsubcategories(){
            	var id=$('#category').val();
            	campaign_arr['cat_id']=id;
            	var host="{{ url('campaign/subcategory/') }}";	
            	$.ajax({
            		type: 'get',
            		data:{'id': id,'_token':CSRF_TOKEN},
            		url: host,
					dataType: "json", // data type of response		
					beforeSend: function(){
						$('.image_loader').show();
					},
					complete: function(){
						$('.image_loader').hide();
					},success:rendercategories

				})
            	getprice(campaign_arr);
            }
            function rendercategories(res){
            	$('#s_category').html('');
            	$.each(res.view_details, function(index, data) {
            		$('#s_category').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
            	});  
            }
            $(document).on('click','#s_category',function(){
            	campaign_arr['s_cat_id']=$('#s_category').val();
            	getprice(campaign_arr);
            });
           // Get Sub Cities
           $(document).on("click", ".state", getcities);

           function getcities(){ 
           	$('.state').css({'background-color':'#fafafa','color':'#444'});
           	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
           	var id= $(this).data('state'); 
           	var host="{{ url('campaign/cities/') }}";	
           	$.ajax({
           		type: 'get',
           		data:{'id': id,'_token':CSRF_TOKEN},
           		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:rendergetcities

	})
           	$(this).css({'background-color':'#ff7f00','color':'#fff'});
           	campaign_arr['state_id']=id;
           	delete campaign_arr.city_id;
           	getprice(campaign_arr);
           	return false;
           }
           function rendergetcities(res){

           	
           	$('.city').html('');
           	$.each(res.view_details, function(index, data) {
           		$('.city').append('<li><a class="cur_city" style="cursor: pointer;" data-city="'+data.id+'">'+data.name+'<i class="fa "></i></a></li>');
           	});   
           }
           $(document).on("click", ".cur_city", function(){
           	$('.cur_city').css({'background-color':'#fafafa','color':'#444'});
           	$(this).css({'background-color':'#ff7f00','color':'#fff'});
           	var city=$(this).data('city');
           	campaign_arr['city_id']=city;
           	getprice(campaign_arr);
           });
           $(document).on("click", ".zone", function(){
           	$('.zone').css({'background-color':'#fafafa','color':'#444'});
           	$(this).css({'background-color':'#ff7f00','color':'#fff'});
           	var zone=$(this).data('zone');
           	campaign_arr['zone']=zone;
           	getprice(campaign_arr);
           });
           $(document).on("click", ".info__button", function(){ 
           	var position=$(this).data('banner');
           	campaign_arr['position']=position;
           	getprice(campaign_arr);
           });
           $(document).on("click", ".mode", function(){ 
           	var mode=$(this).text();
           	campaign_arr['mode']=mode;
           	getprice(campaign_arr);
           });
           $(document).on("click", ".save", function(){ 
           	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
           	campaign_arr['_token']=CSRF_TOKEN;
           	var campaign=JSON.stringify(campaign_arr);
           	$.ajax({
           		type:"POST",
           		url:"{{url('')}}/campaign/Create",
           		data:{'campaign':campaign},
           		dataType:"json",
           		beforeSend: function(){
           			$('.image_loader').show();
           		},
           		complete: function(){
           			$('.image_loader').hide();
           		},success:renderprice
           	});
           	function renderprice(res){
           		$.each(res,function(key,value){
           			$('#campaign_id').val(value.id);
           			$('#height').val(value.height);
           			$('#width').val(value.width);
           			$('.filevalid').text('');
           			$('.filevalid').text('(Allowed: JPG, PNG, GIF,'+value.height+'x'+value.width+')')
           		});

           	}
           	$('.shop-progress a[href="#step2"]').tab('show');
           });
           jQuery.validator.addMethod("imagefile", function(value, element) {
               // allow any non-whitespace characters as the host part
               return this.optional( element ) ||/^(?:[A-Z]:\\fakepath\\)?([a-zA-Z0-9 _-]+)(\.[Jj][Pp][Gg]|\.[Jj][Pp][Ee][Gg]|\.[Pp][Nn][Gg]|\.[Gg][Ii][Ff])$/.test( value );
           }, 'File format not valid');

           $('#campaign_form').validate({
           	rules: {
           		company: {
           			required: true

           		},
           		url:{
           			required:true
           		},
           		ad_image:{
           			required:true,
           			imagefile:true,
           			
           		}
           	},
           	messages: {
           		company: {
           			required: "Title is required",

           		},
           		url:{
           			required:"Destination URL required",
           		},
           		ad_image:{
           			required:"Upload Image",
           		}
           	}

           });
           function filesize(){

           	var fileUpload = $("#file")[0];
           	if (typeof (fileUpload.files) != "undefined") {

           		var reader = new FileReader();
           		reader.readAsDataURL(fileUpload.files[0]);
           		reader.onload = function (e) {
           			var image = new Image();
           			image.src = e.target.result;
           			image.onload = function () {
           				var height = this.height;
           				var width = this.width;
           				var validheight=$('#height').val();
           				var validwidth=$('#width').val();

           				if (height > validheight && width > validwidth) {
           					$('#file-error').text('');
           					$('<label id="file-error" class="error" for="file">File size exceeded</label>').insertAfter('.filevalid');
           					$('#file-error').show();
           					return true;
           				}
           				else{
           					$('#file-error').text('');
           					$('#file-error').hide();
           					return false;
           				}
           			};
           		}
           	} 
           }
           $(document).on('click','.save-form',function(){


           	$('#campaign_form').submit(function(e){
           		var size=filesize();
           		
           		if($('#campaign_form').valid()){
           			if($('#file-error').text()==''){
           				e.preventDefault();
           				var form = 	$('#campaign_form')[0]; 

           				var formData = new FormData(this);

           				$.ajax({
           					type:"POST",
           					url:"{{url('')}}/campaign/moredata",
           					data:formData,
           					dataType:"json",
           					processData: false,
           					contentType: false,
           					beforeSend: function(){
           						$('.image_loader').show();
           					},
           					complete: function(){
           						$('.image_loader').hide();
           					},success:function(res){
           						$('#cur_state').text('');
           						$('#cur_state').text(res.state);
           						$('#cur_city').text('');
           						$('#cur_city').text(res.city);
           						$('#cur_zone').text('');
           						$('#cur_zone').text(res.zone);
           						$('#template_img').attr('');
           						$('#template_img').attr("src","{{url('')}}/uploads/campaigns/original/"+res.image);
           						$('.amount').text(res.price);
           						$('#price').val('');
           						$('#price').val(res.price);
           						$('.shop-progress a[href="#step3"]').tab('show');
           						$('#camp_id').val(res.camp_id);
           					}
           				})
           			}
           		}
           	});
           });
          /* $(document).on('click','.next-step',function(){
           	$('.shop-progress a[href="#step4"]').tab('show');
           });*/
           function getprice(campaign_arr){
           	var campaign=JSON.stringify(campaign_arr);
           	$.ajax({
           		type:"get",
           		url:"{{url('')}}/campaign/price",
           		data:{'campaign':campaign},
           		dataType:"json",
           		beforeSend: function(){
           			$('.image_loader').show();
           		},
           		complete: function(){
           			$('.image_loader').hide();
           		},success:function(res){
           			$('.amount').text('');
           			$('.amount').text(res.amount);
           		}
           	});
           	
           }
       });
   </script>
   @stop;