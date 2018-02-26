@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Currency</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/currency/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Currency <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
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
					
					@foreach ($currency as $currency)
                        <tr class="rm{{ $currency->cid }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $currency->cid }}"/>				 	  
						  </td>
                          <td>{{ $currency->name }}	/ {{ $currency->code }}				
						  </td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $currency->cid }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $currency->cid }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add Currency </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/currency/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
							
							<div class="form-group">
                                    <label class="col-sm-2 control-label">Country Name</label>
                                    <div class="col-sm-6">
                                        <select name="c_id" id="c_id"  class="form-control">									
										 @foreach ($countries as $countries)
											 <option value="{{ $countries->id }}"> 	
											 {{ $countries->name }} 										
											 </option>
										 @endforeach
									 </select>
										<span class="error">{{ ($errors->has('c_id')) ? $errors->first('c_id') : ''}}</span>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Currency Symbol</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="symbol" id="symbol" value="{{ old('symbol') }}"/>
										<span class="error">{{ ($errors->has('symbol')) ? $errors->first('symbol') : ''}}</span>
                                    </div>
                                </div>
									<div class="form-group">
                                    <label class="col-sm-2 control-label"> Currency code</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="code" id="code" value="{{ old('code') }}"/>
										<span class="error">{{ ($errors->has('code')) ? $errors->first('code') : ''}}</span>
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
						<h2>Edit Currency </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/currency/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                       <input type="hidden" value="" name="id" id="edit_id" />
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Country Name</label>
                                    <div class="col-sm-6">
                                        <select name="c_id" id="c_id"  class="form-control">									
										 @foreach ($edit_countries as $countries)
											 <option value="{{ $countries->id }}"> 	
											 {{ $countries->name }} 										
											 </option>
										 @endforeach
									 </select>
										<span class="error">{{ ($errors->has('c_id')) ? $errors->first('c_id') : ''}}</span>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Currency Symbol</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="symbol" id="edit_symbol" value="{{ old('symbol') }}"/>
										<span class="error">{{ ($errors->has('symbol')) ? $errors->first('symbol') : ''}}</span>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <label class="col-sm-2 control-label"> Currency code</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="code" id="edit_code" value="{{ old('code') }}"/>
										<span class="error">{{ ($errors->has('code')) ? $errors->first('code') : ''}}</span>
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
 $(document).on("click", ".edit_blog", edit_category);
	function edit_category(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/currency/getcurrency/') }}";
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
	$('#edit_c_id').val(res.view_details.c_id);
	$('#edit_symbol').val(res.view_details.symbol);
    $('#edit_code').val(res.view_details.code);		
}

 $(document).on("click", ".delete_blog", deleteblog);
	function deleteblog(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/currency/deleted/') }}";
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
 if (confirm("Are you sure delete currency?")) {
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
		alert('Please check atleast one currency');
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