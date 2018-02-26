@extends('layouts.adminmain')

@section('head')
<style>
.tab-pane{    padding-top: 30px;}
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<section id="main-content">
 <section class="wrapper">

    <div class="row">
        <div class="col-md-6">
            <div class="content-box-large">
                <div class="panel-heading">
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
					
                    {!! Form::open(['url' => 'admin/add_product',  'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    <div class="panel-title">Add New Product
                        
                    </div>


                </div>
                <div class="panel-body">
                Listing:
                <Select class="form-control" name="list_id">
                    @foreach($listing as $list)
                    Category:  <option value="{{ $list->id }}" >{{ ucwords($list->title) }}</option>
                    @endforeach
                </select><br>
                Category:
                <Select class="form-control" name="cat_id">
                    @foreach($cat_data as $cat)
                    Category:  <option value="{{ $cat->id }}">{{ ucwords($cat->name) }}</option>
                    @endforeach
                </select>
                <br>

                Name:    <input type="text" name="pro_name" class="form-control">
                <br/>

                Code:    <input type="text" name="pro_code" class="form-control">
                <br/>
				
                Original Price     <input type="text" name="pro_price" class="form-control">
                <br/>
                Discounted  price     <input type="text" name="spl_price" class="form-control">
                <br/>
				Shipping  price     <input type="text" name="shipping_price" class="form-control">
				<br/>
				
                Imgage     <input type="file" name="pro_img" class="form-control">
                <br/>

                Stocks    <input type="text" name="stock" class="form-control">
                <br/>

                Description:    <textarea name="pro_info" class="form-control" rows="5"></textarea>
                <br/>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
				
				<input type="submit" value="Submit" class="btn btn-primary pull-right" style="margin:-5px">

                {!! Form::close() !!}
            </div>



        </div>
    </div>

    
<section>
</section>
<div class="clearfix"></div>  
@endsection
