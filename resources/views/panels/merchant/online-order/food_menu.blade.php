@extends('layouts.merchantmain')

@section('head')
<style>
.tab-pane{    padding-top: 30px;}
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
   
    <div class="page-title">
              <div class="title_left">
                <h3>Menu Category</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-12 col-xs-12">
						<div class="x_panel">
						
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						
						<form name="actionForm" action="{{url('admin/food-menu/destroy_all')}}" method="post" onsubmit="return deleteConfirm();"/> 
							<h2>My Menu Lists <span class="pull-right">
							<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							</span></h2>
						 <div class="x_title">
						  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Menu Name</th>
						  <th>Status</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($merchant_menus as $merchant_menu)
                        <tr class="rm{{ $merchant_menu->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $merchant_menu->id }}"/>				 	  
						  </td>
                          <td>{{ $merchant_menu->menu->name }}</td>
						  <td>
							@if($merchant_menu->status)
								Enable
							@else
								Disable
							@endif
						  </td>
							<td>
								@if($merchant_menu->status)
									<a href="#" class="disable btn btn-primary btn-xs" id="{{ $merchant_menu->id }}">
										Make Disable 
									</a>
								@else
									<a href="#" class="enable btn btn-primary btn-xs" id="{{ $merchant_menu->id }}">
										Make Enable 
									</a>
								@endif	
							</td>
                        </tr> 
					@endforeach
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
<div class="clearfix"></div>  
			</div>	
	
	
	</div>
<script>


// EDit Blog
 $(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host = "{{ url('admin/food-menu') }}" + '/' + id + '/edit';
	var update_url = "{{url('admin/food-menu/update')}}";
	$('#listForm').attr('action', update_url);
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
	var food_menu = res.view_details;
		
	$('#id').val(food_menu.id);
	$('#name').val(food_menu.name);
	$('#status').val(food_menu.status);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the menu?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/admin/food-menu') }}" + '/' + id;
		$.ajax({
			type: 'DELETE',
			data:{'_token':CSRF_TOKEN},
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
					window.location = "{{ url('admin/food-menu') }}";
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

</script>

@stop