@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Listing</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/merchants/listing/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Listing <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Title</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($listing as $listing)
                        <tr class="rm{{ $listing->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $listing->id }}"/>				 	  
						  </td>
                          <td>{{ $listing->title }}</td>
                         <td>
						 @if($listing->status=='Enable')
						 <a href="#" class="enable btn btn-primary btn-xs" id="{{ $listing->id }}"><i class="glyphicon glyphicon-ok"></i> Enable </a>
						 @else
							 <a href="#" class="disable btn btn-primary btn-xs" id="{{ $listing->id }}" ><i class="glyphicon glyphicon-remove"></i> Disable </a>
						 @endif
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $listing->id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $listing->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
                        </tr> 
					@endforeach
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-7 col-xs-12">
					<div class="x_panel" id="add_div" style="">
						<h2>Add Listing </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/merchants/listing/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
							<div class="form-group">
                                    <label class="col-sm-3 control-label"> User Name</label>
                                    <div class="col-sm-6">
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
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Language</label>
                                    <div class="col-sm-6">
									<select name="language_id" id="language_id"  class="form-control">			  
										 @foreach ($language as $language)
										 <option value="{{ $language->id }}">
										 {{ $language->title }}
										 </option>
										 @endforeach
									 </select>						 
                                     
										<span class="error">{{ ($errors->has('language_id')) ? $errors->first('language_id') : ''}}</span>
                                    </div>
                                </div>								
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Main Category</label>
                                    <div class="col-sm-6">
									<select name="category" id="category"  class="form-control category">	<option value="">---Choose---</option>				  
										 @foreach ($category as $category)
										 <option value="{{ $category->c_id }}">
										 {{ $category->c_title }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('category')) ? $errors->first('category') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Sub Category</label>
                                    <div class="col-sm-6">
									<select name="scategory" id="scategory"  class="form-control"> 
										 <option value="">
										 ---Choose---
										 </option>
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('scategory')) ? $errors->first('scategory') : ''}}</span>
                                    </div>
                                </div>
							
							
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"> Listing Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="title" id="b_title" value="{{ old('title') }}"/>
										<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-6">
									<select name="country" id="country"  class="form-control country">						  
										 @foreach ($country as $country)
										 <option value="{{ $country->id }}">
										 {{ $country->name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('country')) ? $errors->first('country') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">States</label>
                                    <div class="col-sm-6">
									<select name="states" id="states"  class="form-control states">
									    <option value="">
										 ---Choose---
										 </option>
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('states')) ? $errors->first('states') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Cities</label>
                                    <div class="col-sm-6">
									<select name="cities" id="cities"  class="form-control">		
										<option value="">
										 ---Choose---
										 </option>
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('cities')) ? $errors->first('cities') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Address 1</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="address1" id="address1" value="{{ old('address1') }}"/>
									<span class="error">{{ ($errors->has('address1')) ? $errors->first('address1') : ''}}</span>                                   
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Address 2</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="address2" id="address2" value="{{ old('address2') }}"/>
                                       
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Postcode</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"/>
									 <span class="error">{{ ($errors->has('postcode')) ? $errors->first('postcode') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Phone No</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="phoneno" id="phoneno" value="{{ old('phoneno') }}"/>
                                       <span class="error">{{ ($errors->has('phoneno')) ? $errors->first('phoneno') : ''}}</span>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <label class="col-sm-3 control-label">Image</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="file" name="photo" id="photo"  />
										<span class="error">{{ ($errors->has('photo')) ? $errors->first('photo') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="meta_tag" class="form-control" id="meta_tag">{{ old('meta_tag') }}</textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left">Listing Description</label>
									  <div class="col-sm-5"><br /><br /></div>
                                    <div class="col-sm-12">
                                        <textarea  class="tinymce" id="description" name="description">{{ old('description') }}</textarea>
										<span class="error">{{ ($errors->has('description')) ? $errors->first('description') : ''}}</span>
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
								  
												  
								  <!-- Add Form End-->
								  
								  
								  
								  
								</div>
								
								
								
					<div class="x_panel" id="edit_div" style=" display:none">
						<h2>Edit Listing </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/merchants/listing/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                       <input type="hidden" value="" name="id" id="edit_id" />
                            <div id="reportArea">
                                
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> User Name</label>
                                    <div class="col-sm-6">
									<select name="user_id" id="edit_user_id"  class="form-control">				  
										 @foreach ($editusers as $user)
										 <option value="{{ $user->uid }}">
										 {{ $user->first_name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('user_id')) ? $errors->first('user_id') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Language</label>
                                    <div class="col-sm-6">
									<select name="language_id" id="edit_language_id"  class="form-control">			  
										 @foreach ($editlanguage as $language)
										 <option value="{{ $language->id }}">
										 {{ $language->title }}
										 </option>
										 @endforeach
									 </select>						 
                                     
										<span class="error">{{ ($errors->has('language_id')) ? $errors->first('language_id') : ''}}</span>
                                    </div>
                                </div>								
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Main Category</label>
                                    <div class="col-sm-6">
									<select name="category" id="edit_category"  class="form-control edit_category">	<option value="">---Choose---</option>				  
										 @foreach ($editcategory as $category)
										 <option value="{{ $category->c_id }}">
										 {{ $category->c_title }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('category')) ? $errors->first('category') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Sub Category</label>
                                    <div class="col-sm-6">
									<select name="scategory" id="edit_scategory"  class="form-control"> 
										 <option value="">
										 ---Choose---
										 </option>
										  @foreach ($editsubcategory as $editsubcategory)
										 <option value="{{ $editsubcategory->c_id }}">
										 {{ $editsubcategory->c_title }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('scategory')) ? $errors->first('scategory') : ''}}</span>
                                    </div>
                                </div>
							
							
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"> Listing Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="title" id="edit_title" value="{{ old('title') }}"/>
										<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-6">
									<select name="country" id="edit_country"  class="form-control edit_country">						  
										 @foreach ($editcountry as $country)
										 <option value="{{ $country->id }}">
										 {{ $country->name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('country')) ? $errors->first('country') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">States</label>
                                    <div class="col-sm-6">
									<select name="states" id="edit_states"  class="form-control edit_states">
									    <option value="">
										 ---Choose---
										 </option>
										  @foreach ($editstates as $editstates)
										 <option value="{{ $editstates->id }}">
										 {{ $editstates->name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('states')) ? $errors->first('states') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Cities</label>
                                    <div class="col-sm-6">
									<select name="cities" id="edit_cities"  class="form-control">		
										<option value="">
										 ---Choose---
										 </option>
										  @foreach ($editcities as $editcities)
										 <option value="{{ $editcities->id }}">
										 {{ $editcities->name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('cities')) ? $errors->first('cities') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Address 1</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="address1" id="edit_address1" value="{{ old('address1') }}"/>
									<span class="error">{{ ($errors->has('address1')) ? $errors->first('address1') : ''}}</span>                                   
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Address 2</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="address2" id="edit_address2" value="{{ old('address2') }}"/>
                                       
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Postcode</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="postcode" id="edit_postcode" value="{{ old('postcode') }}"/>
									 <span class="error">{{ ($errors->has('postcode')) ? $errors->first('postcode') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Phone No</label>
                                    <div class="col-sm-6">
									<input class="form-control" type="text" name="phoneno" id="edit_phoneno" value="{{ old('phoneno') }}"/>
                                       <span class="error">{{ ($errors->has('phoneno')) ? $errors->first('phoneno') : ''}}</span>
                                    </div>
                                </div>			
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Image</label>
                                    <div class="col-sm-6">
									 <div class="col-sm-9">
                                        <input class="form-control" type="file" name="photo" id="photo"  /></div>
										 <div class="col-sm-3"><img src="" id="edit_photo" style="height:40px">
										 </div>
										
										
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="meta_tag" class="form-control" id="edit_meta_tag"></textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="meta_description" id="edit_meta_description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left">Description</label>
									  <div class="col-sm-5"><br /><br /></div>
                                    <div class="col-sm-12">
                                        <textarea  class="tinymce " id="edit_description" name="description"></textarea>
										<span class="error">{{ ($errors->has('description')) ? $errors->first('description') : ''}}</span>
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
					 <!-- Right BAR End-->
					<!--</div>
					 </div>-->
<div class="clearfix"></div>  
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

	var host="{{ url('admin/merchants/listing/getlisting/') }}";
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
var url="{{ url('') }}";
var image_path=res.view_details.image;
	$('#edit_id').val(res.view_details.id);	
	$('#edit_title').val(res.view_details.title);	
	$('#edit_user_id').val(res.view_details.user_id);
	$('#edit_language_id').val(res.view_details.l_id);
	$('#edit_category').val(res.view_details.m_c_id);
	$('#edit_scategory').val(res.view_details.s_c_id);
	$('#edit_country').val(res.view_details.c_id);
	$('#edit_states').val(res.view_details.	state);
	$('#edit_cities').val(res.view_details.city);
	$('#edit_address1').val(res.view_details.address1);
	$('#edit_address2').val(res.view_details.address2);
	$('#edit_postcode').val(res.view_details.pincode);
	$('#edit_phoneno').val(res.view_details.phoneno);	
    $('#edit_photo').attr('src',url+'/uploads/listing/thumbnail/'+image_path);
	$('#edit_meta_tag').val(res.view_details.meta_tag);
	$('#edit_meta_description').val(res.view_details.meta_description);
	
	$(tinymce.get('edit_description').getBody()).html(res.view_details.description);	
}

 $(document).on("click", ".delete_blog", deleteblog);
	function deleteblog(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/merchants/campaigns/deleted/') }}";
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
 if (confirm("Are you sure delete listing?")) {
			var id=res.delete_details.deletedid;
			 $('.rm'+id).hide();
			$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

			}

    return false;
    }
    </script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one blog');
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
	var host="{{ url('admin/merchants/listing/enable/') }}";
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
	var host="{{ url('admin/merchants/listing/disable/') }}";
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
	
	
// Get Sub Category
 $(document).on("change", ".category", getcategory);
	function getcategory(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getsubcategory/') }}";	
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
        },success:rendergetsubcategory
	
	})
	return false;
}
function rendergetsubcategory(res){

       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          }else {
            $('#scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          };
        });   
      }  




// Get States
 $(document).on("change", ".country", getstates);
	function getstates(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getstates/') }}";	
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
        },success:rendergetstates
	
	})
	return false;
}
function rendergetstates(res){

       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#states').append('<option value="'+data.id+'">'+data.name+'</option>');
          }else {
            $('#states').append('<option value="'+data.id+'">'+data.name+'</option>');
          };
        });   
      }	  



// Get Sub Cities
 $(document).on("change", ".states", getcities);
	function getcities(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getcities/') }}";	
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
        },success:rendergetcities
	
	})
	return false;
}
function rendergetcities(res){

       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
          }else {
            $('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
          };
        });   
      }	



/////////////////////////// Edit

// Get Sub Category
 $(document).on("change", ".edit_category", editgetcategory);
	function editgetcategory(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getsubcategory/') }}";	
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
        },success:rendereditgetcategory
	
	})
	return false;
}
function rendereditgetcategory(res){

       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#edit_scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          }else {
            $('#edit_scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          };
        });   
      }  




// Get States
 $(document).on("change", ".edit_country", editgetstates);
	function editgetstates(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getstates/') }}";	
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
        },success:rendereditgetstates
	
	})
	return false;
}
function rendereditgetstates(res){

       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#edit_states').append('<option value="'+data.id+'">'+data.name+'</option>');
          }else {
            $('#edit_states').append('<option value="'+data.id+'">'+data.name+'</option>');
          };
        });   
      }	  



// Get Sub Cities
 $(document).on("change", ".edit_states", editgetcities);
	function editgetcities(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getcities/') }}";	
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
        },success:rendereditgetcities
	
	})
	return false;
}
function rendereditgetcities(res){

       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#edit_cities').append('<option value="'+data.id+'">'+data.name+'</option>');
          }else {
            $('#edit_cities').append('<option value="'+data.id+'">'+data.name+'</option>');
          };
        });   
      }	
	  
</script>

@stop