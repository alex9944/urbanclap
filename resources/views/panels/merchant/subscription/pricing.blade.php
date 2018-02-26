@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Subscription Pricing</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/subscription/pricing/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Pricing <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_menu"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Plan / Price / Country</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					
					  
					@foreach ($pricing as $pricing)
					
					
                        <tr class="rm{{ $pricing->pid }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $pricing->pid }}"/>				 	  
						  </td>
                          <td>{{ $pricing->title }} / {{ $pricing->price }} {{ $pricing->symbol }} / {{ $pricing->sortname }} </td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_menu btn btn-info btn-xs" id="{{ $pricing->pid }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_menu" id="{{ $pricing->pid }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add Pricing </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/subscription/pricing/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
							
							<div class="form-group">
                                    <label class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-6">									
                                     <select name="country" id="country"  class="country form-control">
									  <option value="">Choose country</option>
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
                                    <label class="col-sm-2 control-label">Currency</label>
                                    <div class="col-sm-6">									
                                     <select name="currency"  id="currency" class="form-control">	
									 <option value="">--Choose--</option>
									 </select>
									  <span class="error">{{ ($errors->has('currency')) ? $errors->first('currency') : ''}}</span>
                                    </div>
                                </div>
							<div class="form-group">
                                    <label class="col-sm-2 control-label">Plan</label>
                                    <div class="col-sm-6">									
                                     <select name="plan"  class="form-control">
									  <option value="">Choose Plan</option>
									 @foreach ($plan as $plan)
									 <option value="{{ $plan->id }}">
									 {{ $plan->title }}
									 </option>
									 @endforeach
									 </select>
									 <span class="error">{{ ($errors->has('plan')) ? $errors->first('plan') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Duration</label>
                                    <div class="col-sm-6">									
                                     <select name="duration"  class="form-control">
									  <option value="">Choose Duration</option>
									 @foreach ($duration as $duration)
									 <option value="{{ $duration->id }}">
									 {{ $duration->title }} @if($duration->days_month=='1') Days @else Month @endif
									 </option>
									 @endforeach
									 </select>
									 <span class="error">{{ ($errors->has('duration')) ? $errors->first('duration') : ''}}</span>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label class="col-sm-2 control-label"> Features</label>
                                    <div class="col-sm-10">
									@foreach ($features as $features)
									<input type="checkbox" name="features[]" value="{{ $features->id }}">{{ $features->title }} 
									@endforeach		
									<span class="error">{{ ($errors->has('features')) ? $errors->first('features') : ''}}</span>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Amount</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="price" id="price" value="{{ old('price') }}"/>
										<span class="error">{{ ($errors->has('price')) ? $errors->first('price') : ''}}</span>
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
						<h2>Edit Pricing </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/subscription/pricing/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                             <div id="reportArea">
                                <input type="hidden" value="" name="id" id="edit_id" />
					   
					  <div class="form-group">
                                    <label class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-6">									
                                     <select name="country" id="edit_country"  class="edit_country form-control">
									  <option value="">Choose country</option>
									 @foreach ($edit_country as $country)
									 <option value="{{ $country->id }}">
									 {{ $country->name }}
									 </option>
									 @endforeach
									 </select>
									 <span class="error">{{ ($errors->has('country')) ? $errors->first('country') : ''}}</span>
                                    </div>
                                </div>
							<div class="form-group">
                                    <label class="col-sm-2 control-label">Currency</label>
                                    <div class="col-sm-6">									
                                     <select name="currency"  id="edit_currency" class="form-control">		@foreach ($currency as $currency)
									 <option value="{{ $currency->id }}">
									 {{ $currency->symbol }}
									 </option>
									 @endforeach							  
									 </select>
									 <span class="error">{{ ($errors->has('currency')) ? $errors->first('currency') : ''}}</span>
                                    </div>
                                </div>
							<div class="form-group">
                                    <label class="col-sm-2 control-label">Plan</label>
                                    <div class="col-sm-6">									
                                     <select name="plan" id="edit_plan"  class="form-control">
									  <option value="">Choose Plan</option>
									 @foreach ($edit_plan as $plan)
									 <option value="{{ $plan->id }}">
									 {{ $plan->title }}
									 </option>
									 @endforeach
									 </select>
									 <span class="error">{{ ($errors->has('plan')) ? $errors->first('plan') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Duration</label>
                                    <div class="col-sm-6">									
                                     <select name="duration" id="edit_duration"  class="form-control">
									  <option value="">Choose Duration</option>
									 @foreach ($edit_duration as $duration)
									 <option value="{{ $duration->id }}">
									 {{ $duration->title }} @if($duration->days_month=='1') Days @else Month @endif
									 </option>
									 @endforeach
									 </select>
									 <span class="error">{{ ($errors->has('duration')) ? $errors->first('duration') : ''}}</span>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label class="col-sm-2 control-label"> Features</label>
                                    <div class="col-sm-10">
									<span id="cnt">
									@foreach ($edit_features as $features)
									<input type="checkbox" name="feature[]" value="{{ $features->id }}">{{ $features->title }} 
									@endforeach		
									</span>
									<span class="error">{{ ($errors->has('feature')) ? $errors->first('feature') : ''}}</span>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Amount</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="price" id="edit_price" value="{{ old('price') }}"/>
										<span class="error">{{ ($errors->has('price')) ? $errors->first('price') : ''}}</span>
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
<script>
// Add menu
    $(document).on("click", ".add_menu", add_menu);
	function add_menu(){
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

// EDit menu
 $(document).on("click", ".edit_menu", edit_menu);
	function edit_menu(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/subscription/pricing/getpricing/') }}";
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
	$('#edit_id').val(res.view_details.id);
	$('#edit_country').val(res.view_details.c_id);
	$('#edit_currency').val(res.view_details.cur_id);
	$('#edit_plan').val(res.view_details.p_id);
    $('#edit_duration').val(res.view_details.d_id);		
	$('#edit_price').val(res.view_details.price);	
	$('#cnt input').filter(':checkbox').prop('checked',false);

	var myArray=[];
	if(res.view_details.f_id)
		myArray = res.view_details.f_id.split(',');	
	 for(var i=0;i<myArray.length;i++){      
		 $('#cnt input[value='+myArray[i]+']').filter(':checkbox').prop('checked',true);
    }
	
	 
}

 $(document).on("click", ".delete_menu", deletemenu);
	function deletemenu(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/subscription/pricing/deleted/') }}";
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
 if (confirm("Are you sure delete menu?")) {
			var id=res.delete_details.deletedid;
			 $('.rm'+id).hide();
			$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

			}

    return false;
    }
	
	// Get States
 $(document).on("change", ".country", editgetcur);
	function editgetcur(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).val(); 

	var host="{{ url('admin/subscription/pricing/getcurrency/') }}";	
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
        },success:rendereditgetcurrency
	
	})
	return false;
}
function rendereditgetcurrency(res){
 $('#currency').append('<option value="'+res.view_details.id+'">'+res.view_details.symbol+'</option>');  
      }	  

    </script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one pricing');
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