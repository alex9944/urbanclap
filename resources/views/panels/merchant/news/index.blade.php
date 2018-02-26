@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>News</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/news/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All News <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
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
					@foreach ($news as $news)
                        <tr class="rm{{ $news->n_id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $news->n_id }}"/>				 	  
						  </td>
                          <td>{{ $news->n_title }}</td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $news->n_id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $news->n_id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						<h2>Add News </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  <!-- Add Form Start-->
						 <form method="POST" action="{{url('admin/news/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                      
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> News Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="n_title" id="n_title" value="{{ old('n_title') }}"/>
										<span class="error">{{ ($errors->has('n_title')) ? $errors->first('n_title') : ''}}</span>
                                    </div>
                                </div>
								
								 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">News Url</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="n_slug" id="n_slug" value="{{ old('n_slug') }}" />
										<span class="error">{{ ($errors->has('n_slug')) ? $errors->first('n_slug') : ''}}</span>
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
                                        <textarea name="n_meta_tag" class="form-control" id="n_meta_tag">{{ old('n_meta_tag') }}</textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="n_meta_description" id="n_meta_description">{{ old('n_meta_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left">News Description</label>
									  <div class="col-sm-5"><br /><br /></div>
                                    <div class="col-sm-12">
                                        <textarea  class="tinymce" id="n_description" name="n_description">{{ old('n_description') }}</textarea>
										<span class="error">{{ ($errors->has('n_description')) ? $errors->first('n_description') : ''}}</span>
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
						<h2>Edit News </h2>
						<div class="x_title">
						</div>					
								  <!-- Edit Form Start-->
						 <form method="POST" action="{{url('admin/news/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
                       <input type="hidden" value="" name="id" id="edit_id" />
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> News Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="n_title" id="edit_n_title" />
										<span class="error">{{ ($errors->has('n_title')) ? $errors->first('n_title') : ''}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">News Url</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="n_slug" id="edit_n_slug" />
										<span class="error">{{ ($errors->has('n_slug')) ? $errors->first('n_slug') : ''}}</span>
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
                                        <textarea name="n_meta_tag" class="form-control" id="edit_n_meta_tag"></textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="n_meta_description" id="edit_n_meta_description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left">News Description</label>
									  <div class="col-sm-5"><br /><br /></div>
                                    <div class="col-sm-12">
                                        <textarea  class="tinymce " id="edit_n_description" name="n_description"></textarea>
										<span class="error">{{ ($errors->has('n_description')) ? $errors->first('n_description') : ''}}</span>
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
    $(document).on("click", ".add_blog", add_news);
	function add_news(){  
	var host='{{ url('admin/news/get_users') }}';
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

// EDit Blog
 $(document).on("click", ".edit_blog", edit_news);
	function edit_news(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 

	var host="{{ url('admin/news/getnews/') }}";
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
var image_path=res.view_details.n_image;

	$('#edit_id').val(res.view_details.n_id);
	$('#edit_n_title').val(res.view_details.n_title);
	$('#edit_n_slug').val(res.view_details.n_slug);
    $('#edit_photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#edit_n_meta_tag').val(res.view_details.n_meta_tag);
	$('#edit_n_meta_description').val(res.view_details.n_meta_description);
	$(tinymce.get('edit_n_description').getBody()).html(res.view_details.n_description);
	/*$('#order_by').val(res.view_details.order_by);
	$('#cnt input').filter(':checkbox').prop('checked',false);
	$('#rad input[value='+res.view_details.page_type_id+']').filter(':radio').prop('checked',true);
	$('#parent_id').val(res.view_details.parent_id);
	$('.parent_id').selectpicker('refresh');
	 // $('select[name="page_type_id"]').val(res.view_details.page_type_id);
	  
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
	else
		$(".editpro input[name=dynamic_page_type][value=other]").prop('checked', true); */
}

 $(document).on("click", ".delete_blog", deletenews);
	function deletenews(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/news/deleted/') }}";
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
 if (confirm("Are you sure delete blog?")) {
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
		alert('Please check atleast one news');
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