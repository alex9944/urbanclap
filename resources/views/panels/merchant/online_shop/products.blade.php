@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
          <section id="main-content">
        <section class="wrapper">
          <div class="content-box-large">
            <h1>Products</h1>
			
			@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
			
            <table class="table table-striped table-bordered bulk_action">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Catgeory</th>
                  <th>Product ID</th>
                  <th>Product Name</th>
                  <th>Product Code</th>
                  <th>Product Price</th>
                  <th>Alt Images</th>
                  <th>Stock</th>
                  <th>update</th>
                </tr>
              </thead>
              <?php $count =1;?>
              <tbody>
               @foreach ($Products as $key => $value) 

                <tr>
                  <td> <img src="{{url('')}}/upload/images/small/{{$value->pro_img}}" alt=""
                   width="50px" height="50px"/></td>
                   <td>{{ucwords($value->name)}}</td>
                   <td>{{$value->id}}</td>
                   <td>{{$value->pro_name}}</td>
                   <td>{{$value->pro_code}}</td>
                   <td>{{$value->pro_price}}</td>
                   <td>
                    <?php
                    $Aimgs = DB::table('shop_alt_images')->where('proId', $value->id)
                    ->get();

                    ?>
                    <p> {{count($Aimgs)}} images found</p>
                    <a href="{{url('/')}}/merchant/addAlt/{{$value->id}}"
                     class="btn btn-info" style="border-radius:20px;">
                     <i class="fa fa-plus"></i> Add</a></td>

                     <td>
                      {{$value->stock}}
                    </td>

                    <td><a href="{{url('/')}}/merchant/ProductEditForm/{{$value->id}}"
                     class="btn btn-success btn-small">Edit</a></td>
                   </tr>
                   <?php $count++;
                 ?>
                 @endforeach
               </tbody>
             </table>
           </div>
         </section>
       </section>
       <div class="clearfix"></div>  
	
	
<script>
  $(document).ready(function(){
    <?php for($i=1;$i<60;$i++){?>
  // start loop
  $('#amountDiv<?php echo $i;?>').hide();
  $('#checkSale<?php echo $i;?>').show();
        $('#onSale<?php echo $i;?>').click(function(){  // run when admin need to add amount for sale
          $('#amountDiv<?php echo $i;?>').show();
          $('#checkSale<?php echo $i;?>').hide();
          $('#saveAmount<?php echo $i;?>').click(function(){
            var salePrice<?php echo $i;?> = $('#spl_price<?php echo $i;?>').val();
            var pro_id<?php echo $i;?> = $('#pro_id<?php echo $i;?>').val();
            $.ajax({
              type: 'get',
              dataType: 'html',
              url: '<?php echo url('/admin/addSale');?>',
              data: "salePrice=" + salePrice<?php echo $i;?> + "& pro_id=" + pro_id<?php echo $i;?>,
              success: function (response) {
                console.log(response);
              }
            });
          });
        });
        $('#noSale<?php echo $i;?>').click(function(){ // this when admin dnt need sale
          $('#amountDiv<?php echo $i;?>').hide();
          $('#checkSale<?php echo $i;?>').show();

        });
        //end loop
        <?php }?>
        $('#import_products').hide();
        $('#open_importDiv').click(function(){
          $('#import_products').fadeIn();
          $('#open_importDiv').hide();
        });
      });

    </script>
@stop