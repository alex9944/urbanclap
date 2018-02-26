@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Products</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/merchants/products/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Products <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
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
					@foreach ($products as $products)
                        <tr class="rm{{ $products->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $products->id }}"/>				 	  
						  </td>
                          <td>{{ $products->name }}</td>
                         <td>
						 @if($products->status=='Enable')
						 <a href="#" class="enable btn btn-primary btn-xs" id="{{ $products->id }}"><i class="glyphicon glyphicon-ok"></i> Enable </a>
						 @else
							 <a href="#" class="disable btn btn-primary btn-xs" id="{{ $products->id }}" ><i class="glyphicon glyphicon-remove"></i> Disable </a>
						 @endif
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $products->id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $products->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add Products </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/merchants/products/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      <style>
					  .croppedImg{width:150px;}
					  .img-sz{width: 150px; 
    }
					  </style>
                            <div id="reportArea">
							<div class="form-group">
                                    <label class="col-sm-3 control-label"> Products image 1</label>
                                    <div class="col-sm-6">
								<div id="cropContaineroutput" class="img-sz">
								
								</div>
									<input type="hidden" name="image_1"  id="cropOutput" />
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Products image 2</label>
                                    <div class="col-sm-6">
									<div id="cropContaineroutput1" class="img-sz"></div>
				<input type="hidden" name="image_2" id="cropOutput1"/>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Products image 3</label>
                                    <div class="col-sm-6">
									<div id="cropContaineroutput3" class="img-sz"></div>
				<input type="hidden" name="image_3" id="cropOutput3"  />
                                    </div>
                                </div>
							
							<div class="form-group">
                                    <label class="col-sm-3 control-label"> User Name</label>
                                    <div class="col-sm-6">
									<select name="merchant_id" id="merchant_id"  class="form-control">				  
										 @foreach ($users as $user)
										 <option value="{{ $user->uid }}">
										 {{ $user->first_name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('merchant_id')) ? $errors->first('merchant_id') : ''}}</span>
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
                                    <label class="col-sm-3 control-label"> Category Type</label>
                                    <div class="col-sm-6">
                                       <select name="c_type"  class="form-control c_type">	
									          <option value="">Choose Type</option>
											  <option value="Online Order">Online Order</option>
											  <option value="Online Shopping">Online Shopping</option>
											  <option value="Table Booking">Table Booking</option>
											  <option value="Appoinment Booking">Appoinment Booking</option>
											  <option value="Services Booking">Services Booking</option>
											  <option value="Others">Others</option>										
										</select>
										<span class="error">{{ ($errors->has('c_type')) ? $errors->first('c_type') : ''}}</span>
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
                                    <label class="col-sm-3 control-label"> Products Title</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control typeahead tt-query" autocomplete="off" spellcheck="false" value="{{ old('title') }}" name="title" id="b_title" >
                                     <span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Original Price</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control" value="{{ old('price') }}" name="price" id="b_title" >
                                     <span class="error">{{ ($errors->has('price')) ? $errors->first('price') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Discount Price</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control"  value="{{ old('discount_price') }}" name="discount_price" id="discount_price" >
                                     <span class="error">{{ ($errors->has('discount_price')) ? $errors->first('discount_price') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Products weight</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control" value="{{ old('weight') }}" name="weight" id="weight" >
                                     <span class="error">{{ ($errors->has('weight')) ? $errors->first('weight') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Shipping Amount</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control"  value="{{ old('shipping_amount') }}" name="shipping_amount" id="shipping_amount" >
                                     <span class="error">{{ ($errors->has('shipping_amount')) ? $errors->first('shipping_amount') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Delivery Days</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control" value="{{ old('delivery_days') }}" name="delivery_days" id="delivery_days" >
                                     <span class="error">{{ ($errors->has('delivery_days')) ? $errors->first('delivery_days') : ''}}</span>
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
                                    <label class="col-sm-3 control-label" style="text-align:left">products Description</label>
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
						<h2>Edit Products </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/merchants/products/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                       <input type="hidden" value="" name="id" id="edit_id" />
                            <div id="reportArea">
                                
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> User Name</label>
                                    <div class="col-sm-6">
									<select name="merchant_id" id="edit_merchant_id"  class="form-control">				  
										 @foreach ($editusers as $user)
										 <option value="{{ $user->uid }}">
										 {{ $user->first_name }}
										 </option>
										 @endforeach
									 </select>									 
                                     
										<span class="error">{{ ($errors->has('merchant_id')) ? $errors->first('merchant_id') : ''}}</span>
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
                                    <label class="col-sm-3 control-label"> Category Type</label>
                                    <div class="col-sm-6">
                                       <select name="c_type" id="edit_c_type" class="form-control edit_c_type">	
									         <option value="">Choose Type</option>
											 <option value="Online Order">Online Order</option>
											  <option value="Table Booking">Table Booking</option>
											   <option value="Appoinment Booking">Appoinment Booking</option>
											    <option value="Services Booking">Services Booking</option>
												<option value="Others">Others</option>												
									 </select>
										<span class="error">{{ ($errors->has('c_type')) ? $errors->first('c_type') : ''}}</span>
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
                                    <label class="col-sm-3 control-label"> Products Title</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control typeahead tt-query" autocomplete="off" spellcheck="false" value="{{ old('title') }}" name="title" id="edit_title" >
                                     <span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Original Price</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control" value="{{ old('price') }}" name="price" id="edit_price" >
                                     <span class="error">{{ ($errors->has('price')) ? $errors->first('price') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Discount Price</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control"  value="{{ old('discount_price') }}" name="discount_price" id="edit_discount_price" >
                                     <span class="error">{{ ($errors->has('discount_price')) ? $errors->first('discount_price') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label"> Products weight</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control" value="{{ old('weight') }}" name="weight" id="edit_weight" >
                                     <span class="error">{{ ($errors->has('weight')) ? $errors->first('weight') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Shipping Amount</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control"  value="{{ old('shipping_amount') }}" name="shipping_amount" id="edit_shipping_amount" >
                                     <span class="error">{{ ($errors->has('shipping_amount')) ? $errors->first('shipping_amount') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Delivery Days</label>
                                    <div class="col-sm-6">
									<input type="text" class="form-control" value="{{ old('delivery_days') }}" name="delivery_days" id="edit_delivery_days" >
                                     <span class="error">{{ ($errors->has('delivery_days')) ? $errors->first('delivery_days') : ''}}</span>
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
	<style type="text/css">

.typeahead {
	line-height: 30px;
	outline: medium none;	
}
.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 1px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	margin-top: 2px;
	padding: 1px 0;
	width: 100%;
}
.tt-suggestion {	
	padding: 3px 20px;
}
.tt-suggestion:hover {
	cursor: pointer;
	background-color: #0097CF;
	color: #FFFFFF;
}
.tt-suggestion p {
	margin: 0;
}
</style>
	<script type="text/javascript">
$(document).ready(function(){
    // Defining the local dataset
	  
    var cars = [@foreach ($prod as $products)'{{ $products->name }}',@endforeach];
    
    // Constructing the suggestion engine
    var cars = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: cars
    });
    
    // Initializing the typeahead
    $('.typeahead').typeahead({
        hint: true,
        highlight: true, /* Enable substring highlighting */
        minLength: 1 /* Specify minimum characters required for showing result */
    },
    {
        name: 'cars',
        source: cars
    });
});  
</script>
<script>
// Add Blog
    $(document).on("click", ".add_blog", add_blog);
	function add_blog(){  	
	
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

	var host="{{ url('admin/merchants/products/getproducts/') }}";
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
	$('#edit_title').val(res.view_details.name);	
	$('#edit_merchant_id').val(res.view_details.merchant_id);
	$('#edit_language_id').val(res.view_details.l_id); alert(res.view_details.c_type);
	$('#edit_c_type').val(res.view_details.c_type);
	$('#edit_category').val(res.view_details.m_c_id);
	$('#edit_scategory').val(res.view_details.s_c_id);
	$('#edit_price').val(res.view_details.price);
	$('#edit_discount_price').val(res.view_details.discount_price);
	$('#edit_weight').val(res.view_details.weight);
	$('#edit_shipping_amount').val(res.view_details.shipping_amount);
	$('#edit_delivery_days').val(res.view_details.delivery_days);
	
	$('#edit_country').val(res.view_details.c_id);
	$('#edit_states').val(res.view_details.	state);
	$('#edit_cities').val(res.view_details.city);		
    $('#edit_photo').attr('src',url+'/uploads/products/thumbnail/'+image_path);
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
 if (confirm("Are you sure delete products?")) {
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
	var host="{{ url('admin/merchants/products/enable/') }}";
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
	var host="{{ url('admin/merchants/products/disable/') }}";
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
// Get Category
 $(document).on("change", ".c_type", getctype);
	function getctype(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val();  
	var host="{{ url('admin/merchants/products/getcategory/') }}";	
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
        },success:rendergetctype
	
	})
	return false;
}

function rendergetctype(res){
$('#category').html('');
       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#category').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          }else {
            $('#category').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          };
        });   
      }  	
	
// Get Sub Category
 $(document).on("change", ".category", getcategory);
	function getcategory(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/merchants/products/getsubcategory/') }}";	
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
$('#scategory').html('');
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

	var host="{{ url('admin/merchants/products/getstates/') }}";	
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
$('#states').html('');
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

	var host="{{ url('admin/merchants/products/getcities/') }}";	
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
$('#cities').html('');
       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
          }else {
            $('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
          };
        });   
      }	



/////////////////////////// Edit

// Get Category
 $(document).on("change", ".edit_c_type", getedit_ctype);
	function getedit_ctype(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 
	var host="{{ url('admin/merchants/products/getcategory/') }}";	
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
        },success:rendergetedit_ctype
	
	})
	return false;
}

function rendergetedit_ctype(res){
$('#edit_category').html('');
 $('#edit_category').append('<option value="">Choose Category</option>');
       $.each(res.view_details, function(index, data) {
          if (index==0) {
            $('#edit_category').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          }else {
            $('#edit_category').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
          };
        });   
      }  	
	
// Get Sub Category
 $(document).on("change", ".edit_category", editgetcategory);
	function editgetcategory(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 
 
	var host="{{ url('admin/merchants/products/getsubcategory/') }}";	
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
$('#edit_scategory').html('');
 $('#edit_scategory').append('<option value="">Choose Subcategory</option>');
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

	var host="{{ url('admin/merchants/products/getstates/') }}";	
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
$('#edit_states').html('');
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

	var host="{{ url('admin/merchants/products/getcities/') }}";	
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
$('#edit_cities').html('');
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