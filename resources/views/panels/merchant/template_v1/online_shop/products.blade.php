@extends('layouts.'.$merchant_layout)

@section('head')
   <link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/outsorce.css') }}"> 
   	<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout .'/dropzone/dropzone.css') }}">
	<style>
	.add_new_product {
		background-color: #03A9F4;
	}
	.add_new_product p {
		color: #fff !important;
	}
	</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div>
		
		@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
		@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

		@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
    <div class="services_app__block">
        <ul class="nav nav-pills">
            <li class="active"><a>Shop</a>
        </ul>
		
        <div class="tab-content clearfix">            
            <div class="tab-pane active" id="shop">
				@if($shop_desable)
				<h3 class="appmt_title">Click to Activate</h3>
                <div class="shop_block">
                    <div class="divbox">
                        <a href="{{ url('merchant/online-shop/enable-service') }}" class="ui-link">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="wrapperbox deactive">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Shop</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
				@else
				<h3 class="appmt_title">Click to Deactivate</h3>
                <div class="shop_block">
                    <div class="divbox">
                        <a href="{{ url('merchant/online-shop/disable-service') }}" class="ui-link active">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="wrapperbox">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Shop</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="add_new_products_block">
                    <div class="add_new_product">
                        <p claas="">Add New Products</p>
                    </div>
                   {!! Form::open(['url' => 'merchant/add_product',  'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Category<span class="red">*</span></label>
								<Select class="form-control" name="cat_id">
                        @foreach($cat_data as $cat)
                        Category:  <option value="{{ $cat->id }}" @if(old('cat_id') == $cat->id) selected @endif>{{ ucwords($cat->name) }}</option>
                        @endforeach
                    </select>
                               
                            </div>
                            <div class="form-group">
                                <label for="email">Description</label>
                                <textarea class="form-control" name="pro_info">{{ old('pro_info') }}</textarea>
                            </div>
                           
							<div class="form-group"> 
							<!--<label for="email">Main Image</label>
								<div class="upload-btn-wrapper shop_block_upload1">
										<button class="btn_upload1">Select Files</button>
										<input type="file" name="pro_img" accept="image/*">
								</div>padding:10px;border:1px solid#ccc-->
								<input  class="file-upload " type="file" name="pro_img" accept="image/*" style="display:none"/> 
						<a href="javascript:void(0)" class="upload-button btn_upload1 form-control">
						<i class="fa fa-plus" aria-hidden="true"></i> Select Main Image<span class="red">*</span>
						</a> 
						<div class="text-center" style="margin-top:10px;">
								 <img alt="" src="{{ asset($merchant_layout . '/images/product-dummy.png') }}" class="profile-pic" style="width:100px">
						</div>	
                            </div>
							 <div class="form-group">
                              &nbsp;
                            </div>
							 <div class="form-group">
                                <img src="{{ asset($merchant_layout . '/images/apoyou.png') }}" class="img-responsive">
                            </div>
							     
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Product Name<span class="red">*</span></label>
                                <input type="text" class="form-control" name="pro_name" value="{{ old('pro_name') }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Product Code<span class="red">*</span></label>
                                <input type="text" class="form-control " name="pro_code" value="{{ old('pro_code') }}">
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Original Price<span class="red">*</span></label>
                                    <input type="text" class="form-control"  name="pro_price" value="{{ old('pro_price') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Discount Price</label>
                                    <input type="text" class="form-control" name="spl_price" value="{{ old('spl_price') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Shipping Price</label>
                                    <input type="text" class="form-control" name="shipping_price" value="{{ old('shipping_price') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Stock<span class="red">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
<button type="button" class="btn btn_add_new_product" data-type="minus" data-field="quant[2]">
<span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                        </span>
                                        <input type="text" name="stock" class="form-control input-number" value="{{ old('stock') }}" min="1" max="100">
                                        <span class="input-group-btn">
<button type="button" class="btn btn_add_new_product" data-type="plus" data-field="quant[2]">
  <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- upload new product start -->
                        <div class="clearfix"></div>
						<div class="form-group required">
									
									<div class="col-sm-12">
										<div id="dropzonePreview" class="dropzone grey_color"><p class="text-center">Drop files anyhere to upload</p></div><br />
										<!--<div class="view_banner_section">
											<div class="dropzone">
												<div id="fil"></div>
											</div>
										</div>-->
									</div>
								</div>
                      <!--  <div class="upload-btn-wrapper shop_block_upload">
                            <div class="upload-drop-zone grey_color" id="drop-zone">
                                Drop files anyhere to upload
                            </div>
                            <span class="or"> Or  </span>
                            <button class="btn_upload">Select Files</button>
                            <input type="file" name="myfile">
                        </div>-->
                        <!-- upload new product end -->
                        <div class="new_product_add_block">
                            <ul id="fil">
                                <!--<li>
                                    <img src="{{ asset($merchant_layout . '/images/new_pro1.png') }}" />
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                                <li>
								 <img src="{{ asset($merchant_layout . '/images/new_pro2.png') }}" />
                                   
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                                <li>
                                   <img src="{{ asset($merchant_layout . '/images/new_pro4.png') }}" />
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                                <li>
								 <img src="{{ asset($merchant_layout . '/images/add_pro.png') }}" />                                  
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>-->
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-center add_product_btn">
                            <button class="btn btn-primary btn-add">Add</button>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                  {!! Form::close() !!}
                    <div class="clearfix"></div>
                    <div class="">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                  
                                    <th>Category</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                    <th>Shipping Price</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Products as $key => $value)
								<tr>
                                   
							
                                    <td>{{ucwords($value->categories->name)}}</td>
                                    <td>{{$value->pro_name}}</td>
                                    <td>{{$value->pro_code}}</td>
                                    <td>{{$value->pro_price}}</td>
                                    <td>{{$value->spl_price}}</td>
                                    <td>{{$value->shipping_price}}</td>
                                    <td>{{$value->stock}}</td>
                                    <td><img src="{{url('')}}/upload/images/small/{{$value->pro_img}}" alt=""
                   width="50px" height="50px" class="product_img" /></td>
                                    <td>
                                       <label class="switch">
											<input type="checkbox" id="{{ $value->id }}" class="edit_status" @if($value->status) checked @else @endif >
											<span class="slider round"></span>
										</label>
                                    </td>
                                    <td>
                                       <a href="javascript:void(0);" class="edit_blog custom_pencil" id="{{ $value->id }}" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
										<a href="javascript:void(0);" class="btn-xs delete_blog custom_pencil" id="{{ $value->id }}"><i class="fa fa-trash-o custom_trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
								@endforeach
                            </tbody>
                        </table>
                        <!--<div class="text-center">
                            <button class="btn btn-primary btn-add">Save</button>
                        </div>-->
                    </div>
                </div>
				@endif
			</div>            
        </div>
    </div>
</div>
<!--Edit Model Box Start-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
				
                   {!! Form::open(['url' => 'merchant/online-shop/update-online-shop',  'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Category<span class="red">*</span></label>
								<Select class="form-control" name="cat_id" id="cat_id">
                        @foreach($cat_data as $cat)
                        Category:  <option value="{{ $cat->id }}">{{ ucwords($cat->name) }}</option>
                        @endforeach
                    </select>
                               
                            </div>
                            <div class="form-group">
                                <label for="email">Description</label>
                                <textarea class="form-control" name="pro_info" id="pro_info"></textarea>
                            </div>
                           
							<div class="form-group"> 
							<!--<label for="email">Main Image</label>
								<div class="upload-btn-wrapper shop_block_upload1">
										<button class="btn_upload1">Select Files</button>
										<input type="file" name="pro_img" accept="image/*">
								</div>padding:10px;border:1px solid#ccc-->
								<input  class="file-uploads" type="file" name="pro_img" accept="image/*" style="display:none"/> 
						<a href="javascript:void(0)" class="upload-button btn_upload1 form-control">
						<i class="fa fa-plus" aria-hidden="true"></i> Select Main Image<span class="red">*</span>
						</a> 
						<div class="text-center" style="margin-top:10px;">
								 <img alt="" src="" class="profile-pic-edit" id="main-image-edit" style="width:100px">
						</div>	
                            </div>
							 <div class="form-group">
                              &nbsp;
                            </div>
							 <div class="form-group">
                                <img src="{{ asset($merchant_layout . '/images/apoyou.png') }}" class="img-responsive">
                            </div>
							     
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Product Name<span class="red">*</span></label>
                                <input type="text" class="form-control" name="pro_name" id="pro_name">
                            </div>
                            <div class="form-group">
                                <label for="email">Product Code<span class="red">*</span></label>
                                <input type="text" class="form-control " name="pro_code" id="pro_code">
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Original Price<span class="red">*</span></label>
                                    <input type="text" class="form-control"  name="pro_price" id="pro_price">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Discount Price</label>
                                    <input type="text" class="form-control" name="spl_price" id="spl_price">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Shipping Price</label>
                                    <input type="text" class="form-control" name="shipping_price" id="shipping_price">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Stock<span class="red">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
<button type="button" class="btn btn_add_new_product" data-type="minus" data-field="quant[2]">
<span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                        </span>
                                        <input type="text" name="stock" class="form-control input-number"  id="stock" value="10" min="1" max="100">
                                        <span class="input-group-btn">
<button type="button" class="btn btn_add_new_product" data-type="plus" data-field="quant[2]">
  <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- upload new product start -->
                        <div class="clearfix"></div>
						<div class="form-group required">
									
									<div class="col-sm-12">
										<div id="dropzonePreviews" class="dropzone grey_color"><p class="text-center">Drop files anyhere to upload</p></div><br />
										<!--<div class="view_banner_section">
											<div class="dropzone">
												<div id="fil"></div>
											</div>
										</div>-->
									</div>
								</div>
                      <!--  <div class="upload-btn-wrapper shop_block_upload">
                            <div class="upload-drop-zone grey_color" id="drop-zone">
                                Drop files anyhere to upload
                            </div>
                            <span class="or"> Or  </span>
                            <button class="btn_upload">Select Files</button>
                            <input type="file" name="myfile">
                        </div>-->
                        <!-- upload new product end -->
                        <div class="new_product_add_block">
                            <ul id="editfil">
                               
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-center add_product_btn">
                            <button type="submit" class="btn btn-primary btn-add" style="width: 18%;">Submit</button> 
								<input type="hidden" value="" name="id" id="edit_id" />
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                  {!! Form::close() !!}
                    <div class="clearfix"></div>
			
			
            </div>
           <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>-->
        </div>
    </div>
</div>
<!--Edit Model Box End-->
@stop

@section('footer')
 <script> 
  $(document).ready(function() {
    var table = $('#table').DataTable( {
        responsive: true
    } );
 
    new $.fn.dataTable.FixedHeader( table );
} );
    </script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/dropzone/dropzone.min.js') }}"></script>
			<script>
						
				$(document).ready(function() {


					var readURL = function(input) {
						if (input.files && input.files[0]) {
						var reader = new FileReader();

						reader.onload = function (e) {
						$('.profile-pic').attr('src', e.target.result);
						}

						reader.readAsDataURL(input.files[0]);
						}
					}


					$(".file-upload").on('change', function(){
					readURL(this);
					});

					$(".upload-button").on('click', function() {
					$(".file-upload").click();
					});
				});
					
			</script>
									
									
									<script>
									
									
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

Dropzone.options.dropzonePreview = {
	// Prevents Dropzone from uploading dropped files immediately
	//dropzonePreview.autoDiscover = false;
	url: "{{ url('merchant/listing/image_upload') }}",
	autoProcessQueue: true,
	maxFiles: 3,
	uploadMultiple: false,
	init: function() {
		//var submitButton = document.querySelector("#submit-all")
		myDropzone = this; // closure

		this.on("sending", function(data, xhr, formData) { //alert(jQuery("#title").val());
			formData.append("_token", CSRF_TOKEN);	
			
			var d = new Date();				
			formData.append("timestamp", d.getTime());

		});
		
		this.on("success", function(file_response, obj) {console.log(obj);
			//var obj = jQuery.parseJSON(server_response);
			var status = obj.status;
			if(status == 0) {
				alert(obj.msg);
			} else {
				//$('#added_id').val(obj.id);
				
				//get_item_images();
				
				//myDropzone.removeAllFiles();
			}
		});
		
		this.on("complete", function(file) {
			myDropzone.removeFile(file);
			//get_item_images();
		});
		
		this.on("queuecomplete", function(file) {
			//myDropzone.removeFile(file);
			get_item_images();
		});

	}
};
function get_item_images() {
	$.ajax({
		type: 'POST',
		data:{'_token': CSRF_TOKEN},
		url: "{{ url('merchant/listing/get_uploaded_images') }}",
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:renderListImages

	});
}

function renderListImagesEdit(res){ 
	$('#fil').html('');
	var thumb_url = "{{ url('/upload/images/small') }}/";
	$.each(res, function(index, prolist) { 		
		if(prolist.id) {
		//	$('#fil').append('<div class="dz-preview dz-processing dz-image-preview dz-success" id="list_image_'+prolist.id+'"><div class="dz-details"><img data-dz-thumbnail="" alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'"></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div><div class="dz-error-message"><span data-dz-errormessage=""></span></div><a class="dz-remove" href="javascript:void(0)" onClick="delete_listing_image(\''+prolist.id+'\')">Remove</a></div>');
		 $('#fil').append('<li id="list_image_'+prolist.id+'"><img alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'" /><span><a  href="javascript:void(0)" onClick="delete_listing_image(\''+prolist.id+'\')"><i class="fa fa-times" aria-hidden="true"></i></a></span></li>');
		
		}
	});
}
Dropzone.options.dropzonePreviews = { 
	// Prevents Dropzone from uploading dropped files immediately
	//dropzonePreview.autoDiscover = false;
	url: "{{ url('merchant/listing/image_upload') }}",
	autoProcessQueue: true,
	//maxFiles: 1,
	uploadMultiple: false,
	init: function() {
		//var submitButton = document.querySelector("#submit-all")
		myDropzone = this; // closure

		this.on("sending", function(data, xhr, formData) { //alert(jQuery("#title").val());
			formData.append("_token", CSRF_TOKEN);	
			
			var d = new Date();				
			formData.append("timestamp", d.getTime());

		});
		
		this.on("success", function(file_response, obj) {//console.log(obj);
			//var obj = jQuery.parseJSON(server_response);
			var status = obj.status;
			if(status == 0) {
				alert(obj.msg);
			} else {
				//$('#added_id').val(obj.id);
				
				//get_item_images();
				
				//myDropzone.removeAllFiles();
			}
		});
		
		this.on("complete", function(file) {
			myDropzone.removeFile(file);
			//get_item_images();
		});
		
		this.on("queuecomplete", function(file) {
			//myDropzone.removeFile(file);
			get_item_images_edit();
		});

	}
};
function get_item_images_edit() {
	$.ajax({
		type: 'POST',
		data:{'_token': CSRF_TOKEN},
		url: "{{ url('merchant/listing/get_uploaded_images') }}",
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:renderListImagesedits

	});
}

function renderListImagesedits(res){ 
	
	var thumb_url = "{{ url('/upload/images/small') }}/";
	$.each(res, function(index, prolist) { 	
		if(prolist.id) {
			//$('#fil').append('<div class="dz-preview dz-processing dz-image-preview dz-success" id="list_image_'+prolist.id+'"><div class="dz-details"><img data-dz-thumbnail="" alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'"></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div><div class="dz-error-message"><span data-dz-errormessage=""></span></div><a class="dz-remove" href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')">Remove</a></div>');
		 $('#editfil').append('<li id="list_image_'+prolist.id+'"><img alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'" /><span><a  href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')"><i class="fa fa-times" aria-hidden="true"></i></a></span></li>');
		
		}
	});
}

function delete_listing_image(id)
{
	if (confirm("This action will delete this image permanently. Are you sure want to perform this action?") == true) {
		//
		var id = id;
		$.ajax({
			url:"{{ url('merchant/online-shop/delete_image_table') }}/"+id,
			success:function(data)
			{

				$('#list_image_'+id).hide();
				//get_item_images();

			},

		});
	}
}

function renderListImages(res){ 
	$('#fil').html('');
	var thumb_url = "{{ url('/upload/images/small') }}/";
	$.each(res, function(index, prolist) { 		
		if(prolist.id) {
			//$('#fil').append('<div class="dz-preview dz-processing dz-image-preview dz-success" id="list_image_'+prolist.id+'"><div class="dz-details"><img data-dz-thumbnail="" alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'"></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div><div class="dz-error-message"><span data-dz-errormessage=""></span></div><a class="dz-remove" href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')">Remove</a></div>');
		 $('#fil').append('<li id="list_image_'+prolist.id+'"><img alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'" /><span><a  href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')"><i class="fa fa-times" aria-hidden="true"></i></a></span></li>');
		
		}
	});
}
function delete_listing_image_temp(id)
{
	if (true) {
		//confirm("This action will delete this image permanently. Are you sure want to perform this action?") == 
		var id = id;
		$.ajax({
			url:"{{ url('merchant/online-shop/delete_image') }}/"+id,
			success:function(data)
			{

				$('#list_image_'+id).hide();
				//get_item_images();

			},

		});
	}
}

$(document).ready(function(){
	get_item_images();
	
	// navigate tab on add listing page
	$('.cls_next').click(function(){
		var tab = $(this).data('next');
		activaTab(tab)
	});
	
	function activaTab(tab){
		$('.nav-tabs a[href="#' + tab + '"]').tab('show');
	};
});	
									</script>
<script>
//Status Change

 $(document).on("click", ".edit_status", edit_status);
function edit_status(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host = "{{ url('merchant/online-shop/status-online-shop') }}" + '/' + id;
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	$.ajax({
		type: 'GET',
		//data:{'id': id},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:renderstatus
	
	})
	//return true;
}
function renderstatus(res)
{ 
	var status = res.view_details.status;
	var id = res.view_details.id;
	
}
// EDit 
 $(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host = "{{ url('merchant/online-shop/edit-online-shop') }}" + '/' + id;
	//var update_url = "{{url('merchant/oonline-shop/update-menu-item')}}";
	//$('#listForm').attr('action', update_url);
	$('#add_edit_label').html('Edit');
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	$.ajax({
		type: 'GET',
		//data:{'id': id},
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
function renderListform(res)
{ 
	var listing = res.view_details.listing;
	var menu_item = res.view_details.online_shop;
	$('#editfil').html('');
	var thumb_url = "{{ url('/upload/images/small') }}/";
$.each(res.view_details.pro_img, function(index, data) {
		if(data.alt_img){
			 $('#editfil').append('<li id="list_image_'+data.id+'"><img alt="'+data.alt_img+'" src="'+thumb_url+data.alt_img+'" /><span><a  href="javascript:void(0)" onClick="delete_listing_image(\''+data.id+'\')"><i class="fa fa-times" aria-hidden="true"></i></a></span></li>');
		
			
		}		

    }); 
	// append listing
	/*if(listing != null)
	{
		$('#listing_id').append($('<option>', {
			value: listing.id,
			text: listing.title
		}));
	}*/
	$('#listing_id').val('');
	if(menu_item.listing_id)
		$('#listing_id').val(menu_item.listing_id);
	
		var image_path=menu_item.pro_img;
	$('#edit_id').val(menu_item.id);
	$('#cat_id').val(menu_item.cat_id);
	  $('#main-image-edit').attr('src',thumb_url+image_path);
	//$('#edit_item_type').val(menu_item.item_type);
	$('#rad input[value='+menu_item.item_type+']').filter(':radio').prop('checked',true);
	$('#pro_info').val(menu_item.pro_info);
	$('#pro_name').val(menu_item.pro_name);
	$('#pro_code').val(menu_item.pro_code);
	$('#pro_price').val(menu_item.pro_price);
	$('#spl_price').val(menu_item.spl_price);
	$('#shipping_price').val(menu_item.shipping_price);
	$('#stock').val(menu_item.stock);
	$('#myModal').modal('show');
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the product?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/merchant/online-shop/delete-product') }}";
		$.ajax({
			type: 'POST',
			data:{id:id, '_token':CSRF_TOKEN},
			url: host,
			dataType: "json", // data type of response		
			beforeSend: function(){
			$('.image_loader').show();
			},
			complete: function(){
			$('.image_loader').hide();
			},
			success: function(res)
			{
				if(res.success)
				{
					window.location = "{{ url('merchant/online-shop/products') }}";
				}
				else
				{
					alert(res.msg);
				}

			}
		
		});

	}

    return false;
}
</script>
@stop