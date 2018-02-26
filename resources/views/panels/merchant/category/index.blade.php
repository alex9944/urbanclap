@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Category</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/category/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Category <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
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
					@inject('menus', 'App\Http\Controllers\CategoryController')
					@foreach ($category as $category)
                        <tr class="rm{{ $category->c_id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $category->c_id }}"/>				 	  
						  </td>
                          <td>{{ $category->c_title }}
						  <br />
						  
						  {{ $menus->get_child_category($category->c_id) }}
						  </td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $category->c_id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $category->c_id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add Category </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/category/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Category Name</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="c_title" id="c_title" value="{{ old('c_title') }}"/>
										<span class="error">{{ ($errors->has('c_title')) ? $errors->first('c_title') : ''}}</span>
                                    </div>
                                </div>
								
								 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Slug</label>
                                    <div class="col-sm-6">
                                        <select name="slug"  class="form-control">									
										 @foreach ($categoryslug as $categoryslug)
											 <option value="{{ $categoryslug->id }}"> 	
											 {{ $categoryslug->slug }} 										
											 </option>
										 @endforeach
									 </select>
										<span class="error">{{ ($errors->has('slug')) ? $errors->first('slug') : ''}}</span>
                                    </div>
                                </div>
								 <div class="form-group">
                                    <label class="col-sm-2 control-label">Parent Category</label>
                                    <div class="col-sm-6">
                                        <select name="parent_id" id="parent_id"  class="form-control">
										<option value="0">Parent</option>							
										 @foreach ($pcategory as $pcategory)
											 <option value="{{ $pcategory->c_id }}"> 

									 @if($pcategory->parent_id=='0')								 
									 {{ $pcategory->c_title }} 
         							 @else
									 -- {{ $pcategory->c_title }}									 
									 @endif
									 										
											 </option>
										 @endforeach
									 </select>										
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Language</label>
                                    <div class="col-sm-6">
                                      <select name="language"  class="form-control">									  
										 @foreach ($multilanguage as $multilanguage)
										 <option value="{{ $multilanguage->id }}">
										 {{ $multilanguage->title }}
										 </option>
										 @endforeach
									 </select>
										<span class="error">{{ ($errors->has('language')) ? $errors->first('language') : ''}}</span>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <label class="col-sm-2 control-label">Image</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="file" name="photo" id="photo"  />
										<span class="error">{{ ($errors->has('photo')) ? $errors->first('photo') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="c_meta_tag" class="form-control" id="c_meta_tag">{{ old('c_meta_tag') }}</textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="c_meta_description" id="c_meta_description">{{ old('c_meta_description') }}</textarea>
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
						<h2>Edit Category </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/category/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                       <input type="hidden" value="" name="id" id="edit_id" />
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Category Name</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="c_title" id="edit_c_title" />
										<span class="error">{{ ($errors->has('c_title')) ? $errors->first('c_title') : ''}}</span>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-2 control-label">Slug</label>
                                    <div class="col-sm-6">
                                        <select name="slug" id="edit_slug"  class="form-control">									
										 @foreach ($edit_categoryslug as $edit_categoryslug)
											 <option value="{{ $edit_categoryslug->id }}"> 	
											 {{ $edit_categoryslug->slug }} 										
											 </option>
										 @endforeach
									 </select>
										<span class="error">{{ ($errors->has('slug')) ? $errors->first('slug') : ''}}</span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Parent Category</label>
                                    <div class="col-sm-6">
                                        <select name="parent_id" id="edit_parent_id"  class="form-control">
										<option value="0">Parent</option>							
										 @foreach ($editpcategory as $editpcategory)
											 <option value="{{ $editpcategory->c_id }}"> 	
											 @if($editpcategory->parent_id=='0')								 
											 {{ $editpcategory->c_title }} 
											 @else
											 -- {{ $editpcategory->c_title }}									 
											 @endif											 
											 									
											 </option>
										 @endforeach
									 </select>										
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Language</label>
                                    <div class="col-sm-6">
                                      <select name="language" id="edit_language"  class="form-control">									  
										 @foreach ($edit_multilanguage as $edit_multilanguage)
										 <option value="{{ $edit_multilanguage->id }}">
										 {{ $edit_multilanguage->title }}
										 </option>
										 @endforeach
									 </select>
										<span class="error">{{ ($errors->has('language')) ? $errors->first('language') : ''}}</span>
                                    </div>
                                </div>
								 <div class="form-group">
                                    <label class="col-sm-2 control-label">Image</label>
                                    <div class="col-sm-6">
									 <div class="col-sm-9">
                                        <input class="form-control" type="file" name="photo" id="photo"  /></div>
										 <div class="col-sm-3"><img src="" id="edit_photo" style="height:40px">
										 </div>
										
										
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="c_meta_tag" class="form-control" id="edit_c_meta_tag"></textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="c_meta_description" id="edit_c_meta_description"></textarea>
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

	var host="{{ url('admin/category/getcategory/') }}";
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
var image_path=res.view_details.c_image;

	$('#edit_id').val(res.view_details.c_id);
	$('#edit_c_title').val(res.view_details.c_title);
	$('#edit_slug').val(res.view_details.c_slug_id);
	$('#edit_language').val(res.view_details.c_l_id);
	$('#edit_parent_id').val(res.view_details.parent_id);	
    $('#edit_photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#edit_c_meta_tag').val(res.view_details.c_meta_tag);
	$('#edit_c_meta_description').val(res.view_details.c_meta_description);
}

 $(document).on("click", ".delete_blog", deleteblog);
	function deleteblog(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/category/deleted/') }}";
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
 if (confirm("Are you sure delete category?")) {
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