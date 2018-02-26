@extends('layouts.adminmain')

@section('head')

@stop

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Merchants All Reviews Details</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-7 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/merchants/reviews/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
								@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
								<div class="alert alert-success hidden"></div>
								<h2>All Review Detail List <span class="pull-right"><!--<a href="{{url('admin/merchants/reviews')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  -->
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <!--<th>Date</th>-->
						 <th>Id</th>
                          <th>Name</th>					 
                          <th>Email</th>			 
                          <th>Status</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($reviews as $review)
					
					<?php /*print_r($review->listing->title);*/ ?>
                        <tr class="rm{{ $review->r_id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $review->r_id }}"/>				 	  
						  </td>
						  <td>{{$review->r_id}}</td> 
						 <!--<td><?php echo date('j M y', strtotime($review->created_at));?></td> -->
                          <td>{{ isset($review->user->first_name) ? $review->user->first_name : '' }}</td>
						  <td>{{ isset($review->user->email) ? $review->user->email : '' }}</td>
						  
                         <td> @if($review->approved==0)
						<a href="#" class="enable btn btn-primary btn-xs" id="{{ $review->r_id }}" ><i class="glyphicon glyphicon-remove"></i> Disable </a>
						@else
						<a href="#" class="disable btn btn-primary btn-xs" id="{{ $review->r_id }}"><i class="glyphicon glyphicon-ok"></i> Enable </a>
						@endif
							
                         
						 </td>
						 <td>
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $review->r_id }}" ><i class="fa fa-pencil"></i> View </a>
                            <a href="javascript:void(0);" class="delete_blog btn btn-danger btn-xs " id="{{ $review->r_id }}"><i class="fa fa-trash-o"></i> Delete </a>
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
						<div class="col-md-5 col-xs-12">
							<div class="x_panel" id="add_div" style="display:none;">
								<h2>Add Reviews </h2>
								<div class="x_title">
								</div>
								@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
								<div class="alert alert-success hidden"></div>
								

    </div>
	         <div class="x_panel" id="edit_div" style=" display:none">
			     <h2>Edit Reviews </h2>
					<div class="x_title"></div>					
						<!-- Edit Form Start-->
				<form method="POST" action="{{url('admin/merchants/reviews/updated/')}}"  enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" value="" name="id" id="edit_id" />
							<div id="reportArea">

			<div class="form-group">
			<label class="col-sm-3 control-label">Listing Name :</label>
				<div class="col-sm-8">
				  <input class="form-control" type="text" name="title" id="title" value="" readonly/>
			</div>
			</div>	
         		
			<div class="form-group required">
				<label class="col-sm-3 control-label"> Name	:</label>
					<div class="col-sm-8" >
   					  <input class="form-control" type="text" name="name" id="name" value="" readonly/>
					
				
					 </div>
			</div>		
                                 
								 
			<div class="form-group required">
				<label class="col-sm-3 control-label">Email Id :</label>
					<div class="col-sm-8">
				
						<input class="form-control" type="email" name="email" id="email" value="" readonly/>
				   
					</div>
			</div>		
			
			<div class="form-group required">
				<label class="col-sm-3 control-label">Comments :</label>
					<div class="col-sm-8">
					   <textarea  class="form-control" id="edit_comments" name="comments"></textarea>
				<span class="error">{{ ($errors->has('comments')) ? $errors->first('comments') : ''}}</span>
					</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-3 control-label">Ratings :</label>
					<div class="col-sm-8">
						<select class="form-control" name="rating" id="rating"  >
							<option value="1.0">1.0</option>
							<option value="1.5">1.5</option>
							<option value="2.0">2.0</option>
							<option value="2.5">2.5</option>
							<option value="3.0">3.0</option>
							<option value="3.5">3.5</option>
							<option value="4.0">4.0</option>
							<option value="4.5">4.5</option>
							<option value="5.0">5.0</option>
						</select>
				<span class="error">{{ ($errors->has('rating')) ? $errors->first('rating') : ''}}</span>
					</div>
			</div>
	
	           <div class="form-group required">
					<label class="col-sm-3 control-label">Status</label>
						<div class="col-sm-8">
						    <select name="approved" id="approved"  class="form-control">
								<option value="1">Activated</option>
												<option value="0">De-Activated</option>
												<!--<option value="0">Pending</option> -->
											</select>
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
	//alert(id);

	var host="{{ url('admin/merchants/reviews/reviews') }}";
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
	var reviewsdetails = res.view_details.reviewsdetails;
	var user_detail = res.view_details.user_detail;
	var listing_detail = res.view_details.listing_detail;
	var url="{{ url('') }}";

	$('#edit_id').val(reviewsdetails.r_id);	
	$('#title').val(listing_detail.title);
	$('#name').val(user_detail.first_name);
	$('#email').val(user_detail.email);
	$('#edit_comments').val(reviewsdetails.comments);
    $('#rating').val(reviewsdetails.rating);
    $('#approved').val(reviewsdetails.approved);
   
	
}


// Delete Review
$(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/merchants/reviews/deleted') }}";
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
	if (confirm("Are you sure delete Reviews?")) {
		var id=res.delete_details.deletedid;
		$('.rm'+id).hide();
		$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

	}

	return false;
}

function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one reviews');
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
	var host="{{ url('admin/merchants/reviews/enable') }}";
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
	var host="{{ url('admin/merchants/reviews/disable') }}";
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