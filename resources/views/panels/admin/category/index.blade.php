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
									<h2>All Category <span class="pull-right"><a href="javascript:;" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
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
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $category->c_id }}" ><i class="fa fa-pencil"></i></a> 
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $category->c_id }}"><i class="fa fa-trash-o"></i> </a></td>
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
					<div class="x_panel" id="tags_section" style="display:none;"></div>
					
					<div class="x_panel" id="add_div" style="">
						<h2>Add Category </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif
						
						<div class="alert alert-success hidden"></div>
						
						@if ($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/category/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
								
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Category Name</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="c_title" id="c_title" value="{{ old('c_title') }}"/>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <label class="col-sm-2 control-label">Image</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="file" name="photo" id="photo"  />
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
	$('#tags_section').hide();
	$('#add_div').show();	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

$(document).on("change", "#parent_id", function() {
	$parent_id = $(this).val();
	if($parent_id == 0) {
		$('.category_section').show();
	} else {
		$('.category_section').hide();
		$("input.cls_category_type").prop('checked', false);
	}
});
$(document).on("change", "#edit_parent_id", function() {
	$parent_id = $(this).val();
	if($parent_id == 0) {
		$('.edit_category_section').show();
	} else {
		$('.edit_category_section').hide();
		$("input.edit_cls_category_type").prop('checked', false);
	}
});


// EDit Blog
$(document).on("click", ".edit_blog", edit_category);
function edit_category(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/category/getcategory/') }}";
	$('#add_div').hide();
	$('#tags_section').hide();
	$('#edit_div').show();
	
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
function renderListform(res)
{ 
	var url="{{ url('') }}";
	var image_path=res.view_details.c_image;
	
	var category_type = res.view_details.category_type;
	$("input.edit_cls_category_type").prop('checked', false);
	if(category_type)
	{
		$.each($.parseJSON(category_type), function( index, id ) {
			$("#category_type_" + id).prop('checked', true);
		});
	}

	$('#edit_id').val(res.view_details.c_id);
	//$('#edit_c_type').val(res.view_details.c_type);	
	$('#edit_c_title').val(res.view_details.c_title);
	//$('#edit_slug').val(res.view_details.c_slug_id);
	$('#edit_language').val(res.view_details.c_l_id);
	$('#edit_parent_id').val(res.view_details.parent_id);	
    $('#edit_photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#edit_c_meta_tag').val(res.view_details.c_meta_tag);
	$('#edit_c_meta_description').val(res.view_details.c_meta_description);
	
	$parent_id = res.view_details.parent_id;
	if($parent_id == 0)
		$('.edit_category_section').show();
	else
		$('.edit_category_section').hide();
}

/*** update tags ***/
$(document).on("click", ".edit_tags", edit_tags);
function edit_tags()
{ 
	var id = $(this).attr('id');
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$('.image_loader').show();
	$( "#tags_section" ).load( "{{ url('admin/category/get_tags') }}", { "c_id":  id, '_token':CSRF_TOKEN}, function( response, status, xhr ) {
	  $('.image_loader').hide();
	} ).show();
	$('#add_div').hide();
	$('#edit_div').hide();
}
$(document).on("submit", "#tagsForm", update_languages);
function update_languages()
{ 
	$("#tagsForm .alert").addClass('hidden') ;

	var update_url = "{{url('admin/category/post_tags')}}";
	$('.image_loader').show();
	$( "#tags_section" ).load(
		update_url, 
		$('#tagsForm').serializeArray(), 
		function(response, status, xhr){
			$('.image_loader').hide();
		}
	);
	
	return false;
}

//$(document).ready(function () {
    var counter = 0;

    $(document).on("click", "#addrow", function () {

        counter = $('#myTable tr').length - 2;

        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><input type="text" class="form-control" name="tags[' + counter + ']"/></td>';

        cols += '<td><input type="button" class="ibtnDel"  value="Delete"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });

    $(document).on("click", "table.order-list .ibtnDel", function (event) {
        $(this).closest("tr").remove();
        
        counter -= 1
        //$('#addrow').attr('disabled', false).prop('value', "Add Row");
    });


//});


 $(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	if (confirm("Are you sure delete category?")) {
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
			},
			success:function(res)
			{
				if(res.success)
				{
					window.location = "{{ url('admin/category') }}";
				}
				else
				{
					alert(res.msg);
				}

			}
		
		})
	}

    return false;
}
</script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one blog');
		return false;
	} else {
		if (confirm("Are you sure delete the all selected categories?"))
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

@stop