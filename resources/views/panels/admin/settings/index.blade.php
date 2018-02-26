@extends('layouts.adminmain')

@section('head')
<style>
.tab-pane{    padding-top: 30px;}
</style>
@stop

@section('content')
<?php /*
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
   
    <div class="page-title">
              <div class="title_left">
                <h3>Site Settings</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-12 col-xs-12">
					<div class="x_panel" id="add_div" style="">
						<?php
							$url = url('admin/settings/update');
							$add = 'Update';
						?>
						
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  
						@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
						@endif
						
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#common">Common</a></li>
							<li><a data-toggle="tab" href="#service">Service commission</a></li>
							<li><a data-toggle="tab" href="#localvendor_user">Local vendor by user</a></li>
							<li><a data-toggle="tab" href="#localvendor_merchant">Local vendor by merchant</a></li>
						</ul>
						
						
							
						<div class="tab-content">
							<div id="common" class="tab-pane fade in active">
								<form id="listForm" method="post" action="{{ $url }}" class="form-horizontal">
									<input id="method" name="_method" type="hidden" value="POST">
									<input type="hidden" value="{{ old('id', @$setting->id) }}" name="id" id="id" />
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="col-md-8 col-xs-12">
										<div class="form-group required">
											<label class="col-sm-5 control-label">Default country</label>
											<div class="col-sm-7">
												<select name="country_id" id="country_id"  class="form-control country">						  
													@foreach ($country as $country)
													<option value="{{ $country->id }}" @if($country->id == @$setting->country_id) selected @endif>
														{{ $country->name }}
													</option>
													@endforeach
												</select>	
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-5 control-label">Default city</label>
											<div class="col-sm-7">
												<select name="city_id" id="city_id"  class="form-control country">						  
													@foreach ($cities as $city)
													<option value="{{ $city->id }}" @if($city->id == @$setting->city_id) selected @endif>
														{{ $city->name }}
													</option>
													@endforeach
												</select>	
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-5 control-label">Default currency</label>
											<div class="col-sm-7">
												<select name="currency_id" id="currency_id"  class="form-control country">						  
													@foreach ($currencies as $currency)
													<option value="{{ $currency->id }}" @if($currency->id == @$setting->currency_id) selected @endif>
														{{ $currency->country->name.' / '.$currency->code }}
													</option>
													@endforeach
												</select>		
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-5 control-label">Default city lattiude</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="city_lattitude" id="city_lattitude" value="{{ old('city_lattitude', @$setting->city_lattitude) }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-5 control-label">Default city longitude</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="city_longitude" id="city_longitude" value="{{ old('city_longitude', @$setting->city_longitude) }}"/>
											</div>
										</div>
										<div class="form-group required hidden">
											<label class="col-sm-5 control-label">Merchant commision percent (%)</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="merchant_commision_percent" id="merchant_commision_percent" value="{{ old('merchant_commision_percent', @$setting->merchant_commision_percent) }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-5 control-label">Days to pay merchant after order delivery</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="days_to_pay_merchant" id="days_to_pay_merchant" value="{{ old('days_to_pay_merchant', @$setting->days_to_pay_merchant) }}"/>
											</div>
										</div>
										
										
										<div class="form-group">
											<label class="col-sm-5 control-label">Site email</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="site_email" id="site_email" value="{{ old('site_email', @$setting->site_email) }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Site contact number</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="site_contact_no" id="site_contact_no" value="{{ old('site_contact_no', @$setting->site_contact_no) }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Site address</label>
											<div class="col-sm-7">
												<textarea class="form-control" name="site_address" id="site_address" rows="5">{{ old('site_address', @$setting->site_address) }}</textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Facebook url</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', @$setting->facebook_url) }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Twitter url</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', @$setting->twitter_url) }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Gplus url</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="gplus_url" id="gplus_url" value="{{ old('gplus_url', @$setting->gplus_url) }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Pinterest url</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="pinterest_url" id="pinterest_url" value="{{ old('pinterest_url', @$setting->pinterest_url) }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">Linkedin url</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', @$setting->linkedin_url) }}"/>
											</div>
										</div>
							
										<div class="text-center form-group">							
											<button type="submit" class="text-center btn btn-default">Save</button>
										</div>
									</div>
								</form>
							</div>
							
							<div id="service" class="tab-pane">
								<form method="post" action="{{ url('admin/settings/service') }}" class="form-horizontal">
									<input id="method" name="_method" type="hidden" value="POST">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="col-md-8 col-xs-12">
										<table class="table">
											<tr>
												<th>Service Name</th>
												<th>Commission Type</th>
												<th>Commission Value</th>
											</tr>
										@foreach($services as $service)
											<tr>
												<td>{{$service->name}}</td>
												<td>
													<select name="commission_type[{{$service->id}}]">
														<option value="flat" @if( old('commission_type['.$service->id.']', @$service->commission_type) == 'flat') selected @endif />
														Flat
														</option>
														<option value="percent" @if( old('commission_type['.$service->id.']', @$service->commission_type) == 'percent') selected @endif />
														Percentage
														</option>
													</select>
												</td>
												<td>
													<input class="form-control" type="text" name="commission_value[{{$service->id}}]" value="{{ old('commission_value['.$service->id.']', @$service->commission_value) }}"/>
												</td>
											</tr>
										@endforeach
										</table>
										<div class="text-center form-group">							
											<button type="submit" class="text-center btn btn-default">Save</button>
										</div>
									</div>
								</form>
							</div>
							
							<div id="localvendor_user" class="tab-pane">
								<form method="post" action="{{ url('admin/settings/localvendor-user') }}" class="form-horizontal">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<input type="hidden" value="{{ old('id', @$setting->id) }}" name="id" />
									<div class="col-md-8 col-xs-12">
										<div class="form-group">
											<label class="col-sm-5 control-label">Listing active timing</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="user_local_vendor_active_time" value="{{ old('user_local_vendor_active_time', @$setting->user_local_vendor_active_time) }}"/> hours
											</div>
										</div>
										
										<div class="text-center form-group">							
											<button type="submit" class="text-center btn btn-default">Save</button>
										</div>
									</div>
								</form>
							</div>
							
							<div id="localvendor_merchant" class="tab-pane">
								<form method="post" action="{{ url('admin/settings/localvendor-merchant') }}" class="form-horizontal">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<input type="hidden" value="{{ old('id', @$setting->id) }}" name="id" />
									<div class="col-md-8 col-xs-12">
										<div class="form-group">
											<label class="col-sm-5 control-label">Signup user points</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="signup_user_points" value="{{ old('signup_user_points', @$setting->signup_user_points) }}"/>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-5 control-label">Signup vendor commission</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="signup_vendor_commission" value="{{ old('signup_vendor_commission', @$setting->signup_vendor_commission) }}"/>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-5 control-label">Scanning user points</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="scanning_user_points" value="{{ old('scanning_user_points', @$setting->scanning_user_points) }}"/>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-5 control-label">Scanning vendor commission</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="scanning_vendor_commission" value="{{ old('scanning_vendor_commission', @$setting->scanning_vendor_commission) }}"/>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-5 control-label">Scanning period of time </label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="scanning_user_period" value="{{ old('scanning_user_period', @$setting->scanning_user_period) }}"/> hours
											</div>
										</div>
										
										<div class="text-center form-group">							
											<button type="submit" class="text-center btn btn-default">Save</button>
										</div>
									</div>
								</form>
							</div>
						</div>
								  
												  
								  <!-- Add Form End-->
					</div>
								
								
								
					</div>
					 <!-- Right BAR End-->
					<!--</div>
					 </div>-->
<div class="clearfix"></div>  
			</div>	
	
	
	</div>
	
<script type="text/javascript">
$(document).ready(function(){
    @if( $tab )
		$('.nav-tabs a[href="#{{$tab}}"]').tab('show');
	@endif
});

</script>
*/?>
@stop