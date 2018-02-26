@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>menu</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/menu/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All menu <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_menu"><i class="fa fa-plus"></i> Add </a>  
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
					  @inject('menus', 'App\Http\Controllers\MenuController')
					  
					@foreach ($menu as $menu)
					
					
                        <tr class="rm{{ $menu->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $menu->id }}"/>				 	  
						  </td>
                          <td>{{ $menu->title }}<br />
						  
						  {{ $menus->get_child_menu($menu->id) }}
						  
						  </td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_menu btn btn-info btn-xs" id="{{ $menu->id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_menu" id="{{ $menu->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add menu </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/menu/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
								<div class="form-group">
                                    <label class="col-sm-2 control-label"> Menu Type</label>
                                    <div class="col-sm-10">
									@foreach ($menutype as $menutype)
									<input type="checkbox" name="m_t_id[]" value="{{ $menutype->id }}">{{ $menutype->title }} 
									@endforeach		
									<span class="error">{{ ($errors->has('m_t_id')) ? $errors->first('m_t_id') : ''}}</span>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Menu Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}"/>
										<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								
								 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Menu Url</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug') }}" />
										<span class="error">{{ ($errors->has('slug')) ? $errors->first('slug') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Sub Menu</label>
                                    <div class="col-sm-6">									
                                     <select name="parent_id"  class="form-control">
									  <option value="0">Parent Menu</option>
									 @foreach ($submenu as $submenu)
									 <option value="{{ $submenu->id }}"> 									 
									 @if($submenu->parent_id=='0')									 
									 {{ $submenu->title }} 
         							 @else
									 -- {{ $submenu->title }}									 
									 @endif
									 </option>
									 @endforeach
									 </select>
                                    </div>
                                </div>							
								 <div class="form-group">
                                    <label class="col-sm-2 control-label">Page Type</label>
                                    <div class="col-sm-10">
                                       <input type="radio" value="is_home" name="dynamic_page_type"> Home
										<input type="radio" value="is_product" name="dynamic_page_type"> Product
										<input type="radio" value="is_gallery" name="dynamic_page_type"> Gallery
										<input type="radio" value="is_blog" name="dynamic_page_type"> Blog
										<input type="radio" value="is_contact" name="dynamic_page_type"> Contact Us
										<input type="radio" value="other" name="dynamic_page_type" > Other Page
										<span class="error">{{ ($errors->has('dynamic_page_type')) ? $errors->first('dynamic_page_type') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Menu Order</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="order_by" id="order_by" value="{{ old('slug') }}" />
										<span class="error">{{ ($errors->has('order_by')) ? $errors->first('order_by') : ''}}</span>
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
						<h2>Edit menu </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/menu/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                             <div id="reportArea">
                                <input type="hidden" value="" name="id" id="edit_id" />
					   
					   <div class="form-group">
                                    <label class="col-sm-2 control-label"> Menu Type</label>
                                    <div class="col-sm-10">
									<span id="cnt">
									@foreach ($editmenutype as $editmenutype)
									<input type="checkbox" name="m_t_id[]" value="{{ $editmenutype->id }}">{{ $editmenutype->title }} 
									@endforeach	
									</span> 									
									<span class="error">{{ ($errors->has('m_t_id')) ? $errors->first('m_t_id') : ''}}</span>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Menu Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="title" id="edit_title" value="{{ old('title') }}"/>
										<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								
								 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Menu Url</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="slug" id="edit_slug" value="{{ old('slug') }}" />
										<span class="error">{{ ($errors->has('slug')) ? $errors->first('slug') : ''}}</span>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Sub Menu</label>
                                    <div class="col-sm-6">									
                                     <select name="parent_id" id="parent_id" class="form-control parent_id">
									  <option value="0">Parent Menu</option>
									 @foreach ($editsubmenu as $submenus)
									 <option value="{{ $submenus->id }}">									 
									 @if($submenus->parent_id=='0')									 
									 {{ $submenus->title }} 
         							 @else
									 -- {{ $submenus->title }}									 
									 @endif
									 </option>
									 @endforeach
									 </select>
                                    </div>
                                </div>							
								 <div class="form-group">
                                    <label class="col-sm-2 control-label">Page Type</label>
                                    <div class="col-sm-10 editpro">
                                       <input type="radio" value="is_home" name="dynamic_page_type"> Home
										<input type="radio" value="is_product" name="dynamic_page_type"> Product
										<input type="radio" value="is_gallery" name="dynamic_page_type"> Gallery
										<input type="radio" value="is_blog" name="dynamic_page_type"> Blog
										<input type="radio" value="is_contact" name="dynamic_page_type"> Contact Us
										<input type="radio" value="other" name="dynamic_page_type" checked="checked"> Other Page
										<span class="error">{{ ($errors->has('dynamic_page_type')) ? $errors->first('dynamic_page_type') : ''}}</span>
                                    </div>
                                </div>
					             <div class="form-group">
                                    <label class="col-sm-2 control-label">Menu Order</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="order_by" id="edit_order_by" value="{{ old('order_by') }}" />
										<span class="error">{{ ($errors->has('order_by')) ? $errors->first('order_by') : ''}}</span>
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
	var host='{{ url('admin/menu/get_users') }}';
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

	var host="{{ url('admin/menu/getmenu/') }}";
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
var url="{{ url('') }}";
var image_path=res.view_details.image;

	$('#edit_id').val(res.view_details.id);
	$('#edit_title').val(res.view_details.title);
	$('#edit_slug').val(res.view_details.slug);
	$('#edit_order_by').val(res.view_details.order_by);	
	$('#cnt input').filter(':checkbox').prop('checked',false);
	//$('#rad input[value='+res.view_details.page_type_id+']').filter(':radio').prop('checked',true);
	$('#parent_id').val(res.view_details.parent_id);
	var myArray=[];
	if(res.view_details.m_t_id)
		myArray = res.view_details.m_t_id.split(',');	
	
	 for(var i=0;i<myArray.length;i++){      
		 $('#cnt input[value='+myArray[i]+']').filter(':checkbox').prop('checked',true);
    }
	
	 if(res.view_details.is_contact == 1)
			$(".editpro input[name=dynamic_page_type][value=is_contact]").prop('checked', true);
		else if(res.view_details.is_home == 1)
			$(".editpro input[name=dynamic_page_type][value=is_home]").prop('checked', true);
		else if(res.view_details.is_product == 1)
			$(".editpro input[name=dynamic_page_type][value=is_product]").prop('checked', true);
		else if(res.view_details.is_blog == 1)
			$(".editpro input[name=dynamic_page_type][value=is_blog]").prop('checked', true);
		else if(res.view_details.is_gallery == 1)
			$(".editpro input[name=dynamic_page_type][value=is_gallery]").prop('checked', true);
		else
			$(".editpro input[name=dynamic_page_type][value=other]").prop('checked', true);	
}

 $(document).on("click", ".delete_menu", deletemenu);
	function deletemenu(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/menu/deleted/') }}";
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
    </script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one menu');
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