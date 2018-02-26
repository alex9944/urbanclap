@extends('layouts.merchantmain')

@section('content')

<?php
if(isset($id)){
 $Products = DB::table('shop_products')->where('id',$id)->get();
}else {

  $Products = DB::table('shop_products')->get();
}
 ?>
        <section id="main-content">
            <section class="wrapper">

                <div class="content-box-large">
  {!! Form::open(['url' => 'merchant/sumbitProperty',  'method' => 'post']) !!}
                  <div class="panel-heading col-md-8">
                   <div class="panel-title">Add Property
                     <input type="submit" class="btn btn-success pull-right" value="Submit Property" style="margin:-4px"/>
                   </div>
                   </div>


                    <div class="col-md-5">

                          <b>Product Name:</b>
                        <select class="form-control" name="pro_id">
                              @foreach($Products as $product)
                          <option  value="{{$product->id}}">{{$product->pro_name}}</<option>
                              @endforeach
                          </select><br>

                         Size:  <select class="form-control" name="size">
                                  <option  value="L">L</<option>
                                  <option  value="XL">XL</<option>
                                  <option  value="XXL">XXL</<option>
                            </select><br>


                            Color:  <select class="form-control" name="color">
                                     <option  value="blue">Blue</<option>
                                     <option  value="green">Green</<option>
                                     <option  value="black">Black</<option>
                               </select><br>

                    Price:  <input type="text" name="p_price" class="form-control">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                  </div>

                    {!! Form::close() !!}
                </div>


        </section>
</section>
<div class="clearfix"></div>  
@endsection
