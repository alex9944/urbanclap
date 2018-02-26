@extends('layouts.adminmain')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
  $(document).ready(function(){
    
  });

    </script>
          <section id="main-content">
        <section class="wrapper">
          <!-- import div here-->
          <!-- <div style="padding:10px;" class="col-md-12">
            <form action="{{url('/merchant/import_products')}}" method="post" enctype="multipart/form-data">
              {{csrf_field()}}
              <input type="file" name="file">
              <p style="color:red">{{$errors->first('file')}}</p>
              <input type="submit" value="import" class="btn btn-success"/>
            </form>
          </div> -->
		  <form name="actionForm" action="{{url('admin/delete-all-products')}}" method="post" onsubmit="return deleteConfirm();"/> 
          <div class="content-box-large">
            <h1>Products
			<span class="pull-right"><a href="{{url('admin/addProduct')}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add </a>  
										<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
										<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span>
									</h1>
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
			
            <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
              <thead>
                <tr>
					<th><input type="checkbox" name="check_all" id="check_all" value=""/></th>
					<th>Merchant Name</th>
					<th>Image</th>
					<th>Catgeory</th>
					<th>Product ID</th>
					<th>Product Name</th>
					<th>Product Code</th>
					<th>Product Price</th>
					<th>Alt Images</th>
					<th>Stock</th>
					<th>Action</th>
                </tr>
              </thead>
              <?php $count =1;?>
              <tbody>
               @foreach ($Products as $key => $value) 

                <tr>
					<td>
						<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $value->id }}"/>				 	  
					</td>
					<td> <?php echo $users = DB::table('users')->where('id',$value->merchant_id)->value('first_name'); ?></td>
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
					<a href="{{url('/')}}/admin/addAlt/{{$value->id}}"
					class="btn btn-info" style="border-radius:20px;">
					<i class="fa fa-plus"></i> Add</a></td>

					<td>
					{{$value->stock}}
					</td>

					<td>
					<a href="{{url('/')}}/admin/ProductEditForm/{{$value->id}}" class="btn btn-success btn-xs">Edit</a>
					<a href="javascript:;" class="delete_product btn btn-danger btn-xs" data-id="{{$value->id}}"><i class="fa fa-trash-o"></i>Delete</a>
					</td>
                </tr>
                   <?php $count++;
                 ?>
                 @endforeach
               </tbody>
             </table>
           </div>
		   </form>
         </section>
       </section>
       <div class="clearfix"></div>  

<script type="text/javascript">
$(document).on("click", ".delete_product", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the product?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).data('id'); 
		var host = "{{ url('/admin/delete-product') }}";
		$.ajax({
			type: 'POST',
			data:{'_token':CSRF_TOKEN, id:id},
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
					window.location = "{{ url('admin/products') }}";
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
	
	function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one product');
			return false;
		} else {
			if (confirm("Are you sure delete the all selected products?"))
				return true;
			else
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

</script>

@endsection
