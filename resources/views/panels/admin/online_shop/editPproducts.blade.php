@extends('layouts.adminmain')

@section('content')

<section id="main-content">
  <section class="wrapper">

   <div class="col-md-7">
    <div class="content-box-large ">
     @if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 

     @if(Session::get('error_message'))<div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 
     
	 @if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	 
	 {!! Form::open(['url' => 'admin/editProduct',  'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
     <div class="panel-heading col-md-8">
      <div class="panel-title">Edit Product</div>
    </div>
    
    <div class="panel-body">

      <div class="col-md-8">
        <br/>
        Listing:
        <Select class="form-control" name="list_id">
          @foreach($listing as $list)
          Category:  <option value="{{ $list->id }}" <?php if ($product->listing_id == $list->id) { ?> selected="selected"<?php } ?>>{{ ucwords($list->title) }}</option>
          @endforeach
        </select>
       <br> Category:  <select name="cat_id" class="form-control">
          @foreach($cats as $cat)
          <option value="{{$cat->id}}" <?php if ($product->cat_id == $cat->id) { ?> selected="selected"<?php } ?>>{{ucwords($cat->name)}}</option>
          @endforeach
        </select>
        <br/>
        <input type="hidden" name="id" class="form-control" value="{{$product->id}}">
        Name:    <input type="text" name="pro_name" class="form-control" value="{{$product->pro_name}}">
        <br/>
        Code:    <input type="text" name="pro_code" class="form-control" value="{{$product->pro_code}}">
        <br/>
        Original Price     <input type="text" name="pro_price" class="form-control" value="{{$product->pro_price}}">
        <br/>
        Discounted price: <input type="text" name="spl_price" class="form-control"
        value="{{$product->spl_price}}">
        <br/>
		Shipping  price     <input type="text" name="shipping_price" class="form-control" value="{{$product->shipping_price}}">
		<br/>
				
		Imgage     <input type="file" name="pro_img" class="form-control"> <img src="{{url('')}}/upload/images/small/{{$product->pro_img}}"/><br/>
		<br/>

		Stocks    <input type="text" name="stock" class="form-control" value="{{$product->stock}}">
		<br/>
		
        Description:  <textarea name="pro_info" class="form-control" rows="2">{{$product->pro_info}}</textarea>
        <br/>


          <input type="hidden" name="_token" value="{{ csrf_token() }}">
		  
		  <input type="submit" class="btn btn-success pull-right" value="Update product" style="margin:-4px"/>
        </div>



      </div>
      {!! Form::close() !!}
    </div>
  </div>


  <?php /*<div class="col-md-4">
    <?php $pId = $Products[0]->id;
    $prots = DB::table('shop_products_properties')
    ->where('pro_id', $pId)->get();
    if(count($prots)==0) { } else {?>
    <div class="panel-heading">
     <div class="panel-title">
       Update Proprities
       <a href="" class="btn btn-info pull-right"
       style="margin:-6px; color:#fff">Add more</a>
     </div>
   </div>

   <div class="content-box-large">

    <table class="table table-responsive">
      <tr>
        <td>Size</td>
        <td>Color</td>
        <td>price</td>
        <td>Update</td>
      </tr>
      @foreach($prots as $prot)
      {!! Form::open(['url' => 'admin/editProperty',  'method' => 'post']) !!}
      <tr>
        <input type="hidden" name="pro_id" value="{{$prot->pro_id}}" size="6"/> <!-- products_properties pro_id -->
        <input type="hidden" name="id" value="{{$prot->id}}" size="6"/> <!--// products_properties id -->
        <td><input type="text" name="size" value="{{$prot->size}}" size="6"/></td>
        <td><input type="text" name="color" value="{{$prot->color}}" size="15"/></td>
        <td><input type="text" name="p_price" value="{{$prot->p_price}}" size="6"/></td>
        <td colspan="3" align="right"><input type="submit" class="btn btn-success"
          value="Save" style="margin:-6px; color:#fff"></td>
        </tr>
        {!! Form::close() !!}
        @endforeach

      </table>
      </div>
       <?php }?>

       <hr>

       <div class="content-box-large " align="center">
         <div class="panel-heading">
          <div class="panel-title">
            Update Product Image
          </div>
        </div>

        <img src="{{url('')}}/upload/images/medium/{{$product->pro_img}}"
        alt="" class="img-rounded" width="150px" height="150px"/>
        <br> <br>
        <p><a href="{{url('/admin/EditImage')}}/{{$product->id}}"
         class="btn btn-info">Change Image</a>
       </p>
     </div>
	 
	</div>
	*/?>

   </section>
 </section>
 <div class="clearfix"></div>  
 @endsection
