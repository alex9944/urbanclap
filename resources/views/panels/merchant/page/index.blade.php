@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Pages</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/page/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Pages <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_Page"><i class="fa fa-plus"></i> Add </a>  
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
					@foreach ($page as $page)
                        <tr class="rm{{ $page->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $page->id }}"/>				 	  
						  </td>
                          <td>{{ $page->title }}</td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_Page btn btn-info btn-xs" id="{{ $page->id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_Page" id="{{ $page->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add Page </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/page/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Page Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}"/>
										<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
								
								 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Menu</label>
                                    <div class="col-sm-6">
									<select name="menu_id" id="menu_id"  class="form-control">
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
									 <span class="error">{{ ($errors->has('menu_id')) ? $errors->first('menu_id') : ''}}</span>
                                    </div>
                                </div>								 
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="meta_tag" class="form-control" id="meta_tag">{{ old('meta_tag') }}</textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left">Page Description</label>
									  <div class="col-sm-5"><br /><br /></div>
                                    <div class="col-sm-12">
                                        <textarea  class="tinymce" id="description" name="description">{{ old('description') }}</textarea>
										<span class="error">{{ ($errors->has('description')) ? $errors->first('description') : ''}}</span>
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
						<h2>Edit Page </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/page/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                       <input type="hidden" value="" name="id" id="edit_id" />
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Page Name</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="title" id="edit_title" />
										<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Menu</label>
                                    <div class="col-sm-6">
                                        <select name="menu_id" id="edit_menu_id"  class="form-control">
									 @foreach ($editsubmenu as $editsubmenu)
									 <option value="{{ $editsubmenu->id }}">								 
									 @if($editsubmenu->parent_id=='0')									 
									 {{ $editsubmenu->title }} 
         							 @else
									 -- {{ $editsubmenu->title }}									 
									 @endif
									 </option>
									 @endforeach
									 </select>
                                    </div>
                                </div>								 
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="meta_tag" class="form-control" id="edit_meta_tag"></textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="meta_description" id="edit_meta_description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left">Page Description</label>
									  <div class="col-sm-5"><br /><br /></div>
                                    <div class="col-sm-12">
                                        <textarea  class="tinymce " id="edit_description" name="description"></textarea>
										<span class="error">{{ ($errors->has('description')) ? $errors->first('description') : ''}}</span>
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
// Add Page
    $(document).on("click", ".add_Page", add_Page);
	function add_Page(){  
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

// EDit Page
 $(document).on("click", ".edit_Page", edit_page);
	function edit_page(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id');	

	var host="{{ url('admin/page/getpage/') }}";
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
	$('#edit_menu_id').val(res.view_details.menu_id);
	$('#edit_meta_tag').val(res.view_details.meta_tag);
	$('#edit_meta_description').val(res.view_details.meta_description);
	$(tinymce.get('edit_description').getBody()).html(res.view_details.description);	
}

 $(document).on("click", ".delete_Page", deletePage);
	function deletePage(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/page/deleted/') }}";
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
 if (confirm("Are you sure delete page?")) {
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
		alert('Please check atleast one page');
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