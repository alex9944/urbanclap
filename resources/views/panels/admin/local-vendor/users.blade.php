@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Local Vendor Users Details</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-6 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/local-vendor/users/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
								<h2>All Local Vendor Users <span class="pull-right"><a href="{{url('admin/local-vendor/users')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <!--<th>Date</th>-->
						  <th>Id</th>
                          <th>Name</th>					 
                          <!--<th>Email</th> -->			 
                          <th>Status</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($localvendor as $user)
					<!--<?php print_r($user->id);?> -->
                        <tr class="rm{{ $user->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $user->id }}"/>				 	  
						  </td>
						  <td>{{$user->id}}</td>
						 <!-- <td><?php echo date('j M y', strtotime($user->created_at));?></td> -->
                          <td>{{ $user->owner_name }}</td>
                         <td>@if($user->status=='Enable')
						<a href="#" class="enable btn btn-primary btn-xs" id="{{ $user->id }}"><i class="glyphicon glyphicon-ok"></i> Enable </a>
						@else
						<a href="#" class="disable btn btn-primary btn-xs" id="{{ $user->id }}" ><i class="glyphicon glyphicon-remove"></i> Disable </a>
						@endif
							<!-- @if($user->merchant_status==0)
								Pending
							 @elseif($user->merchant_status==1)
								Activated
							 @else
								 De-Activated
							 @endif -->
                         
						 </td>
						 <td>
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $user->id }}" ><i class="fa fa-pencil"></i> View </a>
                            <a href="javascript:void(0);" class="delete_blog btn btn-danger btn-xs " id="{{ $user->id }}"><i class="fa fa-trash-o"></i> Delete </a>
							</td>
                        </tr> 
					@endforeach
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
					<!-- Right BAR Start-->
						<div class="col-md-6 col-xs-12">
							<div class="x_panel" id="add_div" style="">
								<h2>Add Local Vendor  </h2>
								<div class="x_title">
								</div>
								@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
								<div class="alert alert-success hidden"></div>
								<!-- Add Form Start-->
								<form method="POST" action="{{url('admin/local-vendor/users/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">

									<div id="reportArea">
										<div class="form-group">
											<label class="col-sm-3 control-label"> User Name</label>
											<div class="col-sm-8">
												<select name="user_id" id="user_id"  class="form-control">				  
													@foreach ($users as $user)
													<option value="{{ $user->uid }}">
														{{ $user->first_name }}
													</option>
													@endforeach
												</select>									 

												<span class="error">{{ ($errors->has('user_id')) ? $errors->first('user_id') : ''}}</span>
											</div>
										</div>
                                <div class="form-group required">
										<label class="col-sm-3 control-label">Category	</label>
										<div class="col-sm-8">
											<select class="form-control" name="category" id="category"  >
												<option value="all">All</option>
												<option value="drinks">Drinks</option>
												<option value="eatable">Eatable</option>
												<option value="platform_goods">Platform Goods</option>
											</select>
										</div>
									</div>         
								<div class="form-group required">
                                    <label class="col-sm-3 control-label">  Owner Name</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}"/>
										<span class="error">{{ ($errors->has('owner_name')) ? $errors->first('owner_name') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group required">
											<label class="col-sm-3 control-label"> Site Title</label>
											<div class="col-sm-8">
												<input class="form-control" type="text" name="title" id="b_title" value="{{ old('title') }}"/>
												<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
											</div>
										</div>
            <div class="form-group required">
					<label class="col-sm-3 control-label">Working Hours</label>
						<div class="col-sm-5">
						From
						<select name="start_time" id="start_time"  class="">	
						@foreach ($times as $time_value)
						<option value="{{ $time_value }}" @if( old('start_time') == $time_value ) selected @endif>{{ $time_value }}
						</option>
						@endforeach
					   </select>
					   <select name="start_time_ar" id="start_time_ar"  class="">
						<option value="am" @if( old('start_time_ar') == $time_value ) selected @endif>AM</option>
						<option value="pm" @if( old('start_time_ar') == $time_value ) selected @endif>PM</option>
						</select>
						</div>
						<div class="col-sm-4">
						To
						<select name="end_time" id="end_time"  class="">	
						@foreach ($times as $time_value)
						<option value="{{ $time_value }}" @if( old('end_time') == $time_value ) selected @endif>
						{{ $time_value }}
						</option>
						@endforeach
						</select>
						<select name="end_time_ar" id="end_time_ar"  class="">
						<option value="am" @if( old('end_time_ar') == $time_value ) selected @endif>AM</option>
						<option value="pm" @if( old('end_time_ar') == $time_value ) selected @endif>PM</option>
						</select>
					</div>
				</div>    							
					<div class="form-group required">
						<label class="col-sm-3 control-label" >Description</label>
								<div class="col-sm-8">
								<textarea  class="form-control" id="description" name="description">{{ old('description') }}</textarea>
								<span class="error">{{ ($errors->has('description')) ? $errors->first('description') : ''}}</span>
					   </div>
					</div>				
				<div class="form-group required">
					<label class="col-sm-3 control-label">Address </label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="address" id="address" value="{{ old('address') }}"/>
								<span class="error">{{ ($errors->has('address')) ? $errors->first('address') : ''}}</span>                                   
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Image</label>
						<div class="col-sm-8">
							<input class="form-control" type="file" name="photo" id="photo"  />
							<span class="error">{{ ($errors->has('photo')) ? $errors->first('photo') : ''}}</span>
						</div>
				</div>
										
                <div class="form-group required">
				   <label class="col-sm-3 control-label">Latitude </label>
						<div class="col-sm-8">
						<input class="form-control" type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"/>
							<span class="error">{{ ($errors->has('latitude')) ? $errors->first('latitude') : ''}}</span>
						</div>
				</div>
				<div class="form-group required">
					<label class="col-sm-3 control-label">Longitude</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"/>
							<span class="error">{{ ($errors->has('longitude')) ? $errors->first('longitude') : ''}}</span>
					    </div>
				</div>
				<div class="form-group required">
					<label class="col-sm-3 control-label">Phone No</label>
						<div class="col-sm-8">
					<input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone') }}"/>
						<span class="error">{{ ($errors->has('phone')) ? $errors->first('phone') : ''}}</span>
						</div>
				</div>

				<div class="form-group required">
						<label class="col-sm-3 control-label">Website Url</label>
							<div class="col-sm-8">
							<input class="form-control" type="text" name="website" id="website" value="{{ old('website') }}">
							<span class="error">{{ ($errors->has('website')) ? $errors->first('website') : ''}}</span>
						     </div>
				</div>
									
										 <?php /* <div class="form-group required" id="holiday_days">
											<label class="col-sm-3 control-label">Holidays</label>
											<div class="col-sm-7">
												<input type="checkbox"  name="holidays[]" class="holidays" value="Sunday">Sunday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Monday">Monday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Tuesday">Tuesday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Wednesday">Wednesday
												<br/><input type="checkbox"  name="holidays[]" class="holidays" value="Thursday">Thursday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Friday">Friday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Saturday">Saturday
												<br/>
												<input type="checkbox"  name="holidays[]" class="holidays" value="no">No Holiday
											</div>
										</div>  */?> 


										 
										<?php /*
										<div class="form-group required">
											<label class="control-label col-sm-8">Click on the map to set lattitude and logitude</label>
										</div>

										<div class="form-group">
											<div class="col-sm-12">
												<div id="map" style="width:99%; height:300px;"></div>
											</div>
										</div>
										<div class="form-group required">
											<label class="control-label col-sm-3">Lattitude and Longitude</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="lat_long" id="lat_long" value="{{ old('lat_long') }}"/>
												<input type="hidden" name="lattitude" id="lattitude" value="{{ old('lattitude') }}">
												<input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
											</div>
										</div> */ ?>
										
										
										
										
										
										
										
										<?php /*<div class="form-group required">
									<label class="col-sm-3 control-label">Status</label>
									<div class="col-sm-6">
										<select name="merchant_status" class="form-control">
											<option value="1" @if( old('merchant_status') == 1 ) selected @endif>Activated</option>
											<option value="2" @if( old('merchant_status') === 0 ) selected @endif>De-Activated</option>
											<option value="0" @if( old('merchant_status') === 0 ) selected @endif>Pending</option>
										</select>
									</div>
								</div> */?>
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="ln_solid"></div>
										<div class="form-group">
											<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
												<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>

											</div>
										</div>



										<div class="clearfix visible-lg"></div>
									</div>
								</form>


								<!-- Add Form End-->

    </div>
	         <div class="x_panel" id="edit_div" style=" display:none">
			     <h2>Edit Local Vendor </h2>
					<div class="x_title"></div>					
						<!-- Edit Form Start-->
				<form method="POST" action="{{url('admin/local-vendor/users/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" value="" name="id" id="edit_id" />
							<div id="reportArea">

			<div class="form-group">
				<label class="col-sm-3 control-label"> User Name</label>
					<div class="col-sm-8">
						<select name="user_id" id="edit_user_id"  class="form-control">@foreach ($users as $user)
							<option value="{{ $user->uid }}">
										{{ $user->first_name }}
							</option>
						@endforeach
						</select>
				<span class="error">{{ ($errors->has('user_id')) ? $errors->first('user_id') : ''}}</span>
					</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-3 control-label">Category	</label>
							<div class="col-sm-8">
							<select class="form-control" name="category" id="edit_category"  >
								<option value="all">All</option>
								<option value="drinks">Drinks</option>
								<option value="eatable">Eatable</option>
								<option value="platform_goods">Platform Goods</option>
							</select>
							</div>
			</div>							
			<div class="form-group required">
				<label class="col-sm-3 control-label">Owner Name </label>
					<div class="col-sm-8">
					  <input class="form-control" type="text" name="owner_name" id="edit_owner_name" value="{{ old('owner_name') }}"/>
				<span class="error">{{ ($errors->has('owner_name')) ? $errors->first('owner_name') : ''}}</span>
					 </div>
			</div>		
                                 
								 
			<div class="form-group required">
				<label class="col-sm-3 control-label">Title</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="title" id="edit_title" value="{{ old('title') }}"/>
				<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
					</div>
			</div>
			
			<div class="form-group required">
				<label class="col-sm-3 control-label">Working Hours</label>
					<div class="col-sm-4">
						From
						<select name="start_time" id=""  class="start_time">	
								@foreach ($times as $time_value)
								<option value="{{ $time_value }}" @if( old('start_time') == $time_value ) selected @endif>
								{{ $time_value }}
								</option>
								@endforeach
						</select>
						<select name="start_time_ar" id=""  class="start_time_ar">
								<option value="am" @if( old('start_time_ar') == $time_value ) selected @endif>AM</option>
								<option value="pm" @if( old('start_time_ar') == $time_value ) selected @endif>PM</option>
						</select>
					</div>
					<div class="col-sm-5">
						To
						<select name="end_time" id=""  class="end_time">	
								@foreach ($times as $time_value)
								<option value="{{ $time_value }}" @if( old('end_time') == $time_value ) selected @endif>
							   {{ $time_value }}
								</option>
								@endforeach
					    </select>
						<select name="end_time_ar" id=""  class="end_time_ar">
								<option value="am" @if( old('end_time_ar') == $time_value ) selected @endif>AM</option>
								<option value="pm" @if( old('end_time_ar') == $time_value ) selected @endif>PM</option>
						</select>
					</div>
				</div>	
			
			<div class="form-group required">
				<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">
						<textarea  class="form-control" id="edit_description" name="description"></textarea>
				<span class="error">{{ ($errors->has('description')) ? $errors->first('description') : ''}}</span>
					</div>
			</div>
			
			<div class="form-group required">
				<label class="col-sm-3 control-label">Address</label>
					 <div class="col-sm-8">
					<input class="form-control" type="text" name="address" id="edit_address" value="{{ old('address') }}"/>
					<span class="error">{{ ($errors->has('address')) ? $errors->first('address') : ''}}</span>
					</div>
			</div>
			
			<div class="form-group required">
					<label class="col-sm-3 control-label">Image</label>
						<div class="col-sm-7">
							<div class="col-sm-9">
							<input class="form-control" type="file" name="photo" id="photo"  /></div>
					<div class="col-sm-3"><img src="" id="edit_photo" style="height:40px">
				     </div>
                        </div>
				</div>
		
			<div class="form-group required">
				<label class="col-sm-3 control-label">Latitude</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="latitude" id="edit_latitude" value="{{ old('latitude') }}">
					<span class="error">{{ ($errors->has('latitude')) ? $errors->first('latitude') : ''}}</span>					
					</div>
			</div>
			
			<div class="form-group required">
				<label class="col-sm-3 control-label">Longitude</label>
					<div class="col-sm-8">
						<input class="form-control" type="text" name="longitude" id="edit_longitude" value="{{ old('longitude') }}">
					<span class="error">{{ ($errors->has('longitude')) ? $errors->first('longitude') : ''}}</span>					
					</div>
			</div>
		
     			<div class="form-group">
					<label class="col-sm-3 control-label">Phone No</label>
							<div class="col-sm-8">
							<input class="form-control" type="text" name="phone" id="edit_phone" value="{{ old('phone') }}"/>
					<span class="error">{{ ($errors->has('phone')) ? $errors->first('phone') : ''}}</span>
				            </div>
				</div>
				
				<div class="form-group required">
				<label class="col-sm-3 control-label">Website Url</label>
					<div class="col-sm-8">
						<input type="text" class="form-control website" name="website" id="" value="{{ old('website') }}">
					<span class="error">{{ ($errors->has('website')) ? $errors->first('website') : ''}}</span>					
					</div>
			</div>
				
			
				
					
					<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="ln_solid"></div>
							<div class="form-group">
							  <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">				
			<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>
								</div>
						     </div>



											<div class="clearfix visible-lg"></div>
										</div>
									</form>


									<!-- Edit Form End-->




								</div>
	</div>

<script>
// Add Blog
$(document).on("click", ".add_blog", add_blog);
function add_blog(){  
	var host='{{ url('admin/blog/get_users') }}';
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

// EDit Blog
$(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host="{{ url('admin/local-vendor/users/vendorlisting') }}";
	$('#add_div').hide();
	$('#edit_div').fadeIn("slow");
	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:renderListform

	})
	return false;
}
function renderListform(res){ 
	console.log(res);
	var url="{{ url('') }}";
	var image_path=res.view_details.image;
	$('#edit_id').val(res.view_details.id);	
	$('#edit_user_id').val(res.view_details.user_id);
	$('#edit_owner_name').val(res.view_details.owner_name);
	$('#edit_category').val(res.view_details.category);
	$('#edit_title').val(res.view_details.title);
    $('#edit_description').val(res.view_details.description);
    $('#edit_address').val(res.view_details.address);
    $('#edit_photo').attr('src',url+'/uploads/localvendor/thumbnail/'+image_path);	
	$('#edit_latitude').val(res.view_details.latitude);
	$('#edit_longitude').val(res.view_details.longitude);
	$('#edit_phone').val(res.view_details.phone);
    $('.website').val(res.view_details.website);	
	var time=res.view_details.working_hours;
	//alert(time);
	if(time!=null){
		var timing=time.split('-');
		var datastring = timing[0];//console.log(datastring);
		var myArray = datastring.split(/(\d+)/).filter(Boolean);//console.log(myArray);
		$('.start_time').val(myArray[0]);
		$('.start_time_ar').val(myArray[1]);
		var timestring = timing[1];//console.log(datastring);
		var timingar = timestring.split(/(\d+)/).filter(Boolean);//console.log(myArray);
		$('.end_time').val(timingar[0]);
		$('.end_time_ar').val(timingar[1]);
	}
	/*var holiday=jQuery.parseJSON(res.view_details.holidays);
	if(holiday!=null){
		$('.holidays').each(function(){
			for(var i=0;i<holiday.length;i++){
				if($(this).val()==holiday[i]){
					$(this).attr('checked','true');
				}
			}
		})
	}*/
	
	//$(tinymce.get('edit_description').getBody()).html(res.view_details.description);	
}


// Delete Users
$(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/local-vendor/users/deleted') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:deleteStatus

	})
	return false;
}
function deleteStatus(res){ 
	if (confirm("Are you sure delete Users?")) {
		var id=res.delete_details.deletedid;
		$('.rm'+id).hide();
		$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

	}

	return false;
}

function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one Users');
			return false;
		}	
	}
	$(document).ready(function(){
		$('#check_all').on('click',function(){
			if(this.checked){
				$('.checkbox').each(function(){
					this.checked = true;
				});
			}else{
				$('.checkbox').each(function(){
					this.checked = false;
				});
			}
		});

		$('.checkbox').on('click',function(){
			if($('.checkbox:checked').length == $('.checkbox').length){
				$('#check_all').prop('checked',true);
			}else{
				$('#check_all').prop('checked',false);
			}
		});
	});


//Change Status Enable

$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/local-vendor/users/enable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:enableStatus

	})
	return false;
}
function enableStatus(res){
	location.reload();
}



//Change Status Disable

$(document).on("click", ".disable", disable);
function disable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/local-vendor/users/disable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:disableStatus

	})
	return false;
}
function disableStatus(res){ 
	location.reload();
}
</script>


@stop