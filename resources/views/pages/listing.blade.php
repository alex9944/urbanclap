@extends('layouts.'.$user_layout)

@section('header')
<style type="text/css">
#srchBlock .dropdown-toggle
{
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	bor
	webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}
.listGridBoxdiv
{
	min-height: 250px;
}
#listFilterContent
{
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075); 
	border-radius: 4px;
}

#srchBlock #listFilterBlock .dropdown-toggle{
	border:none !important;
}
	#listFilterBlock .filter{
		    float: right;
    padding-top: 9px;
    padding-bottom: 9px;
	}
.thumb_img_gridlist{	
	background-repeat: no-repeat;
    background-size: cover;
    background-position: top center;
    position: relative;
    min-height: 119px;
    display: block;
}	

.m-t51
{
margin-top: 8px;
	
}
	
</style>
@stop

@section('content')
@include('partials.status-panel')


<!-- start breadcrumbs -->
<div class="container">
	<div class="row">
		<ul class="breadcrumb">
			<li><a href="{{url('/')}}">Home</a></li>
			<li class="active">Listings</li>
		</ul>
	</div>
</div>
</div>
<!-- end breadcrumbs -->
<?php
use App\Models\MerchantServices;
?>

<!-- header-filter -->
<div class="fliter-header">
	<div class="container">
		<div class="row" id="srchBlock">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 p-t15">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
						<!-- <input id="Location" type="text" class="form-control" name="" placeholder="Location / Area"> -->
						<!-- <select class="form-control selectpicker" name="Location" id="Location" data-size="8" data-live-search="true" title="Location / Area">
						</select> -->
						<input id="autocomplete" name="location" placeholder="Enter your address" onFocus="geolocate()" type="text" class="form-control"></input>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 p-t15">
				@if($subcategories)
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></span>
						<!-- <input id="Location" type="text" class="form-control" name="" placeholder="Sub Category"> -->
						<select class="form-control selectpicker" name="subCategory" id="subCategory" data-size="8" data-live-search="true" title="Sub Category">
							@foreach($subcategories as $subcategory)
							<option value="{{ $subcategory->c_id }}" data-slug="{{ $slug }}">{{ $subcategory->c_title }}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 p-t15">
				@if($listings->count() != 0)
				<a href="javascript:void(0);" onclick="javascript:$('#listingListContainer').hide();$('#listingGridContainer').show();$('#localvendorContainer').hide();"> <i class="fa fa-square pad-10" aria-hidden="true">&nbsp; Grid</i></a>
				<a href="javascript:void(0);" onclick="javascript:$('#listingGridContainer').hide();$('#listingListContainer').show();$('#localvendorContainer').hide();"><i class="fa fa-align-left pad-10 hidden-xs hidden-sm" aria-hidden="true">&nbsp; List</i></a>
				@endif
				<span class="dropdown" id="listFilterBlock">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="glyphicon glyphicon-filter filter">&nbsp;Filter</i></a>

					<ul class="dropdown-menu" style="margin-left: 70px;">
						<li>
							<a class="radius" data-km="300">Around 3KM</a>
							<a  class="radius" data-km="400">Around 4Km</a>
							<a  class="radius" data-km="500">Around 5KM</a>
						</li>
					</ul>
				</span> 
			</div>
		</div>
	</div>
</div>
<!-- END header-filter -->

<div class="container-fluid p-tb30 ">
	<div class="row">
		<div class="col-lg-9 col-md-9 col-sm-10 col-xs-12">
			<div class="row" id="listingGridContainer" style="display:block;">
				<?php
				$listingGridContent='';
				$listingListContent='';
				if(count($listings)>0)
					{ ?>
						@foreach ($listings as $list) 
							<?php 
							if(!isset($list->listing_merchant->subscribed_category->category_type))
								continue;
							?>

						<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-b10 listGridBoxdiv">
							<div class="catagories-box">
								<span class="glyphicon glyphicon-bookmark icon-bookmark "></span>
								<a class="thumb_img_gridlist" href="{{ url('') }}/{{$list->slug}}" style="background-image: url('{{ url('') }}/uploads/listing/original/{{ $list->image }}');">
								<!--<img src="{{url('')}}/uploads/listing/thumbnail/{{$list->image}}" alt="" class="img-responsive p-b5"/> -->
								</a>
								<div class="row pad-5">
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
										<div class="title-catagories"><a href="{{ url('') }}/{{$list->slug}}">{{$list->title}}</a> <span class="glyphicon glyphicon-map-marker clr-red"></span></div>
										<span class="f-11">{{$list->address1}}, {{isset($list->listing_city->name) ? $list->listing_city->name : ''}}</span>
									</div>	
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<div class="badge bg-green responsive-p-right">{{number_format($list->avg,1)}}</div>
										<div class="responsive-p-right f-11">{{$list->reviews}} Reviews</div>
									</div>		  
								</div>
								<div class="row m-tb10 cat-box-icon-box">
									<?php 
									$enabled_services = $list->services;//print_r($enabled_services);
									?>
									@if($enabled_services)
										@foreach($enabled_services as $service)
											<?php
											$service_id = $service['service_id'];
											?>
											@if(isset($services_by_id[$service_id]['slug']))
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 align-center bdr-right">
												<?php
												
												if($services_by_id[$service_id]['slug'] == 'table') {
													
													$icon = 'book-table.png';
										            $txt = 'Book a Table';
													$url = url($list->slug) . '#book_a_table';
													
												}
												if($services_by_id[$service_id]['slug'] == 'food') {
													$icon = 'cart-icon.png';
													$txt = 'Order Online';
													$url = url($list->slug) . '#order';
												}
												if($services_by_id[$service_id]['slug'] == 'shop') {
													$icon = 'cart-icon.png';
													$txt = 'Order Online';
													$url = url($list->slug) . '#shop';
												}
												if($services_by_id[$service_id]['slug'] == 'appointment') {
													$icon = 'appoinment-icon.png';
													$txt = 'Appointment Booking';
													$url = url($list->slug) . '#book_appointment';
												}
												?>
												<a href="{{$url}}">
												<img src ="{{url('')}}/uploads/listing/original/{{$icon}}" style="width:50px;height:50px;"/> 
												<!--<div class="glyphicon {{$icon}} cat-box-icon"></div>  -->
												<span class="f-10">{{$txt}}</span>
												</a>
											</div>
											@endif
											
										@endforeach
										    
									@endif
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 align-center">
										<a href="{{ url('') }}/{{$list->slug}}#call">
										<img src ="{{url('')}}/uploads/listing/original/call-us.png" style="width:50px;height:50px;"/> 
										<!--<div class="glyphicon glyphicon-earphone cat-box-icon"></div>-->
										<span class="f-10">Contact</span>
										</a>
									</div>
								</div>
							</div>
						</div>
						@endforeach
						<?php }
						else
							{?>
								<div class="alert alert-warning" role="alert"align="center">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4><i class="glyphicon glyphicon-warning-sign"></i> Info!</h4>
									<p>No data found.</p>
								</div>
								<?php }
								?>
							</div>
							<div class="row" id="listingListContainer" style="display:none;">
								<?php
								$listingGridContent='';
								$listingListContent='';
								if(count($listings)>0)
									{ ?>
										@foreach ($listings as $list) 
											<?php 
											if(!isset($list->listing_merchant->subscribed_category->category_type))
												continue;
											?>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-b10">
											<div class="catagories-box" id="listbox">
												<span class="glyphicon glyphicon-bookmark icon-bookmark "></span>
												<div class="row">
													<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12 col-lg-4 col-md-3 col-sm-6 col-xs-12 m-t51">
														<a  class="thumb_img_gridlist" href="{{ url('') }}/{{$list->slug}}" style="background-image: url('{{ url('') }}/uploads/listing/original/{{ $list->image }}');">
													<!--<img src="{{url('')}}/uploads/listing/thumbnail/{{$list->image}}" alt="" class="img-responsive"/>--> </a>
													</div>

													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 m-t5">
														<div class="title-catagories-listing"><a href="{{ url('') }}/{{$list->slug}}">{{$list->title}}</a><span class="glyphicon glyphicon-map-marker clr-red"></span></div>
														<div class="f-11 listing-titles-b-m">{{$list->address1}}, {{isset($list->listing_city->name) ? $list->listing_city->name : ''}}</div>
														<div class="badge" id="list">{{number_format($list->avg,1)}}</div> &nbsp;
														<span class="f-11">{{$list->reviews}} Reviews</span>
													</div>	
													<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
														<div class="row m-tb10 listing-box">															
															<?php 
															$enabled_services = $list->services;
															?>
															@if($enabled_services)
																@foreach($enabled_services as $service)
																	<?php
																	$service_id = $service['service_id'];
																	?>
																	@if(isset($services_by_id[$service_id]['slug']))
																	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 align-center bdr-right">
																		<?php
																		
																		if($services_by_id[$service_id]['slug'] == 'table') {
																			
																			$icon = 'book-table.png';
																			$txt = 'Book a Table';
																			$url = url($list->slug) . '#book_a_table';
																		}
																		if($services_by_id[$service_id]['slug'] == 'food') {
																			$icon = 'cart-icon.png';
																			$txt = 'Order Online';
																			$url = url($list->slug) . '#order';
																		}
																		if($services_by_id[$service_id]['slug'] == 'shop') {
																			$icon = 'cart-icon.png';
																			$txt = 'Order Online';
																			$url = url($list->slug) . '#shop';
																		}
																		if($services_by_id[$service_id]['slug'] == 'appointment') {
																			$icon = 'appoinment-icon.png';
																			$txt = 'Appointment Booking';
																			$url = url($list->slug) . '#book_appointment';
																		}
																		?>
																		<a href="{{$url}}">
																		<img src ="{{url('')}}/uploads/listing/original/{{$icon}}" style="width:50px;height:50px;"/> 
																		<!--<div class="glyphicon {{$icon}} cat-box-icon"></div>  -->
																		<span class="f-10">{{$txt}}</span>
																		</a>
																	</div>
																	@endif
																@endforeach
															@endif
															<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 align-center">
																<a href="{{ url('') }}/{{$list->slug}}#call">
																<img src ="{{url('')}}/uploads/listing/original/call-us.png" style="width:50px;height:50px;"/> 
																<span class="f-10">Contact</span>
																</a>
															</div>
														</div>
													</div>		  
												</div>

											</div>
										</div>
										@endforeach
										<?php }
										else
											{?>
												<div class="alert alert-warning" role="alert"align="center">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
													<h4><i class="glyphicon glyphicon-warning-sign"></i> Info!</h4>
													<p>No data found.</p>
												</div>

												<?php }
												?>
											</div>
											<div class="row" id="localvendorContainer" style="display:none;">
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
											<div class="row hidden-xs hidden">
												<div class="col-lg-12 m-b10">
													<img src="{{ url('') }}/uploads/resturant/ad1.jpg" alt="" class="img-responsive p-b5">
												</div>
												<div class="col-lg-12 m-b10">
													<img src="{{ url('') }}/uploads/resturant/ad2.jpg" alt="" class="img-responsive p-b5">
												</div>
												<div class="col-lg-12 m-b10">
													<img src="{{ url('') }}/uploads/resturant/ad3.jpg" alt="" class="img-responsive p-b5">
												</div>
												<div class="col-lg-12 m-b10">
													<img src="{{ url('') }}/uploads/resturant/ad3.jpg" alt="" class="img-responsive p-b5">
												</div>
												<div class="col-lg-12 m-b10">
													<img src="{{ url('') }}/uploads/resturant/ad3.jpg" alt="" class="img-responsive p-b5">
												</div>
											</div>
										</div>
									</div>


								</div>
@stop
								
@section('footer')
<script type="text/javascript">
	$(document).ready(function () {

		$(function(){
			var listingData=<?=$listings?>;
			$.each(listingData, function(i, value) {
				$('#fltHotelName').append($('<option>').text(value.title).attr('value', value.title));
			});
			$('#fltHotelName').selectpicker('refresh');
		});
		$('#alllocation').change(function(){
//change_location();
location.reload;
});	
		$(document).on('click','#localvendor',function(){
			var location=$('#set_loc').val();
			var loc_vendor='';
			$('#localvendorContainer').html('');
				var today = new Date(); // for now
				var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
				var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
				$.ajax({
					type:'get',
					url:"{{url('')}}/getlocalvendor",
					data:{'location':location},
					dataType:'json',
					success:function(res){
						$.each(res,function(key,value){

							if(new Date(value.listing_date)>=new Date(date)){

								if(timeToSeconds(value.end_time) > timeToSeconds(time)){
									loc_vendor+='<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-b10 listGridBoxdiv"><div class="catagories-box"><span class="glyphicon glyphicon-bookmark icon-bookmark "></span><a href="#"><img src="http://127.0.0.1/uploads/localvendor/'+value.image_name+'" alt="" class="img-responsive p-b5"></a><div class="row"><div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><div class="title-catagories"><a href="#">'+value.name+'</a> <span class="glyphicon glyphicon-map-marker clr-red">'+value.website+'</span></div><span class="f-11">'+value.description+'</span></div></div></div></div>';
								}
							}
							else{

							}
						});
						if(loc_vendor==''){
							loc_vendor='<div class="alert alert-warning" role="alert"align="center"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="glyphicon glyphicon-warning-sign"></i> Info!</h4><p>No data found.</p></div>';
						}
						$('#localvendorContainer').append(loc_vendor);
						$('#listingGridContainer').hide();
						$("#listingListContainer").hide();
						$('#localvendorContainer').show();
					}

				});
			});
		$(document).on('click','#Classifieds',function(){
			$('#listingGridContainer').show();
			$("#listingListContainer").hide();
			$('#localvendorContainer').hide();
		});
		function timeToSeconds(time) {
			time = time.split(/:/);
			return time[0] * 3600 + time[1] * 60 + time[2];
		}
	});


</script>

@stop