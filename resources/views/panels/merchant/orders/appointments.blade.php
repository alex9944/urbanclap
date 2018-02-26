@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Booking Appointments</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/blog/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Booking Appointments <span class="pull-right">  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Booking Id</th>
						   <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($orders as $orders)
                        <tr class="rm{{ $orders->b_id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $orders->id }}"/>				 	  
						  </td>
                          <td>{{ str_pad($orders->id, 10, "0", STR_PAD_LEFT)  }}</td>
						 
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="view_order btn btn-info btn-xs" id="{{ $orders->id }}" ><i class="fa fa-folder"></i> View </a>
							 
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $orders->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>View Booking Appointments </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/blog/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="fil">                                
								
                            </div>
                    </form>
			  
								  <!-- Add Form End-->

								</div>

						<div class="x_panel" id="vieworder" style=" display:none">
						<h2>Booking Appointments </h2>
						<div class="x_title">
						</div>					
							
     <table class="table table-striped table-bordered bulk_action">
						   
						   <tr>
						   <th>Booking Id</th>
						   <th>Payment Type</th>
						   </tr>
						   <tr>
						   <td id="order_id"></td>
						   <td id="payment_type"></td>
						   </tr>
						   
						     <tr>
						   <th>Amount</th>
						   <th>Payment Status</th>
						   </tr>
						   <tr>
						   <td id="amount"></td>
						   <td id="status"></td>
						   </tr>
						   
						   <tr>
						   <th>Email</th>
						   <th>Phone No</th>
						   </tr>
						   <tr>
						   <td id="email"></td>
						   <td id="phone"></td>
						   </tr>
	 </table>
	 
	  <table class="table table-striped table-bordered bulk_action">
						   
						   <tr>
						   <th>Billing Address</th>						  
						   </tr>
						   <tr>
						   <td id="b_address_1"></td>						 
						   </tr>
						    <tr>
						   <td id="b_address_2"></td>						 
						   </tr>
						    <tr>
						   <td id="b_city"></td>						 
						   </tr>
						    <tr>
						   <td id="b_state"></td>						 
						   </tr>
						    <tr>
						   <td id="b_country"></td>						 
						   </tr>
						    <tr>
						   <td id="b_pincode"></td>						 
						   </tr>
	 </table>
	 
	 <table class="table table-striped table-bordered bulk_action">
						   
						   <tr>
						   <th>Shipping Address</th>						  
						   </tr>
						   <tr>
						   <td id="s_address_1"></td>						 
						   </tr>
						    <tr>
						   <td id="s_address_2"></td>						 
						   </tr>
						    <tr>
						   <td id="s_city"></td>						 
						   </tr>
						    <tr>
						   <td id="s_state"></td>						 
						   </tr>
						    <tr>
						   <td id="s_country"></td>						 
						   </tr>
						    <tr>
						   <td id="s_pincode"></td>						 
						   </tr>
	 </table>
	 
                
						</div>
								
					</div>
					 <!-- Right BAR End-->
					<!--</div>
					 </div>-->
<div class="clearfix"></div>  
			</div>
    </div>
	
	<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Product </h4>
      </div>
      <div class="modal-body" id="viewItem">
       
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>
</div>

	
<script>
function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
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

	var host="{{ url('admin/orders/getorders/') }}";
	$('#add_div').hide();
	$('#vieworder').hide();
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
        },success:renderListformorder
	
	})
	return false;
}
function renderListformorder(res){ 

$('#reportArea').html(''); 

	$.each(res.view_details, function(index, prolist) {  
		$('#reportArea').append('<tr><td>'+prolist.name+'</td><td>'+prolist.first_name+'</td><td>'+prolist.qty+'</td><td>'+prolist.total+'</td> <td><a href="javascript:void(0);" class="view_item btn btn-info btn-xs" id="'+prolist.uid+'" ><i class="fa fa-folder"></i> View </a></td></tr>');
	});

	
}

$(document).on("click", ".view_item", view_item);
	function view_item(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/orders/getitem/') }}";
	$('#myModal').modal('show');
	
	
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
        },success:renderListformitem
	
	})
	return false;
}
function renderListformitem(res){ 
//var url="{{ url('') }}";
//var image_path=res.view_details.b_image;
$('#viewItem').html(''); 
$('#viewItem').append('<table><tr><th>Product Title</th><td>&nbsp; :</td><td>'+res.view_details.name+'</td></tr><tr><th>&nbsp; </th><td>&nbsp; </td><td>&nbsp; </td></tr><tr><th>Merchant Name</th><td>&nbsp; :</td><td>'+res.view_details.first_name+'</td></tr><tr><th>&nbsp; </th><td>&nbsp; </td><td>&nbsp; </td></tr><tr><th>Product Qty</th><td>&nbsp; :</td><td>'+res.view_details.qty+'</td></tr><tr><th>&nbsp; </th><td>&nbsp; </td><td>&nbsp; </td></tr><tr><th>Product Price</th><td>&nbsp; :</td><td>'+res.view_details.total+'</td></tr></table>');

	
}


$(document).on("click", ".view_order", view_order);
	function view_order(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/orders/vieworder/') }}";
	$('#add_div').hide();
	$('#edit_div').hide("slow");
	$('#vieworder').fadeIn("slow");
	
	
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
//var url="{{ url('') }}";
//var image_path=res.view_details.b_image;

var oid=pad(res.view_details.id, 10); 
	$('#order_id').html(oid);
	$('#payment_type').html(res.view_details.payment_type);
	$('#email').html(res.view_details.c_email);
	$('#phone').html(res.view_details.c_phone);
	$('#s_address_1').html(res.view_details.s_address_1);
	$('#s_address_2').html(res.view_details.s_address_2);
	$('#s_city').html(res.view_details.s_city);
	$('#s_state').html(res.view_details.s_state);
	$('#s_country').html(res.view_details.s_country);
	$('#s_pincode').html(res.view_details.s_pincode);
	$('#b_address_1').html(res.view_details.b_address_1);
	$('#b_address_2').html(res.view_details.b_address_2);
	$('#b_city').html(res.view_details.b_city);
	$('#b_state').html(res.view_details.b_state);
	$('#b_country').html(res.view_details.b_country);
	$('#b_pincode').html(res.view_details.b_pincode);

	/*
    $('#edit_photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#edit_b_meta_tag').val(res.view_details.b_meta_tag);
	$('#edit_b_meta_description').val(res.view_details.b_meta_description);
	$(tinymce.get('edit_b_description').getBody()).html(res.view_details.b_description);*/	
}

//$('#myModal').modal('show')

 $(document).on("click", ".delete_blog", deleteblog);
	function deleteblog(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/orders/deleted/') }}";
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
 if (confirm("Are you sure delete orders?")) {
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
		alert('Please check atleast one orders');
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

@stop