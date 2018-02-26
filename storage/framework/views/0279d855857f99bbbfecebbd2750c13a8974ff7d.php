<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Services</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="<?php echo e(url('admin/services/destroy')); ?>" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Services <span class="pull-right"><a href="<?php echo e(url('admin/services')); ?>" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Id</th>
                          <th>Title</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					
					<?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="rm<?php echo e($service->id); ?>">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="<?php echo e($service->id); ?>"/>				 	  
						  </td>
                          <td><?php echo e($service->id); ?></td>
                          <td><?php echo e($service->name); ?></td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" data-id="<?php echo e($service->id); ?>" ><i class="fa fa-pencil"></i></a> 
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" data-id="<?php echo e($service->id); ?>"><i class="fa fa-trash-o"></i> </a></td>
                        </tr> 
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-7 col-xs-12">								
								
					<div class="x_panel" id="edit_div">
						<?php
						$id = '';
						$add = 'Add';
						$url = url('admin/services/added');
						if(old('id') != '')
						{
							$id = old('id');
							$add = 'Edit';
							$url = url('admin/services/updated');
						}
						?>
                        
                        <h2><span id="add_div_label"><?php echo e($add); ?></span> Services </h2>
						<div class="x_title">
						</div>	
                        
                        <?php if(Session::get('message')): ?> <div class="alert alert-success" role="alert"><?php echo e(Session::get('message')); ?> </div><?php endif; ?>
                        <?php if(Session::get('error_message')): ?> <div class="alert alert-danger" role="alert"><?php echo e(Session::get('error_message')); ?> </div><?php endif; ?>
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>				
								  <!-- Edit Form Start-->
						 <form id="Servicefrm" method="POST" action="<?php echo e($url); ?>"  enctype="multipart/form-data" class="form-horizontal">
                       		<input type="hidden" value="<?php echo e(old('id')); ?>" name="id" id="id" />
                            <div id="reportArea">
								
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label"> Category</label>
                                    <div class="col-sm-6">
                                        <select name="category_id" id="category_id"  class="form-control"> 
                                            <option value="">---Choose---</option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->c_id); ?>" <?php if( old('category_id') == $category->c_id ): ?> selected <?php endif; ?>>
                                                <?php echo e($category->c_title); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>	
                                    </div>
                                </div>
                                
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label"> Service Name</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"> Page Title</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="page_title" id="page_title" value="<?php echo e(old('page_title')); ?>" />
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Tag</label>
                                    <div class="col-sm-6">
                                        <textarea name="meta_keywords" class="form-control" id="meta_keywords"><?php echo e(old('meta_keywords')); ?></textarea>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Description</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" name="meta_description" id="meta_description"><?php echo e(old('meta_description')); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-6">
                                        <select name="status" id="status"  class="form-control">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>                               
								
								<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
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

// EDit Blog
$(document).on("click", ".edit_blog", edit_row);
function edit_row()
{ 
  	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   	var id= $(this).data('id'); 
	
	var url = "<?php echo e(url('admin/services/updated')); ?>";
	$('#Servicefrm').attr('action', url);

	var host="<?php echo e(url('admin/services/get')); ?>";
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	
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
	$('#id').val(res.view_details.id);	
	$('#category_id').val(res.view_details.category_id);
	$('#name').val(res.view_details.name);
	$('#page_title').val(res.view_details.page_title);	
	$('#meta_keywords').val(res.view_details.meta_keywords);
	$('#meta_description').val(res.view_details.meta_description);
	$('#status').val(res.view_details.status);
}

$(document).on("click", ".delete_blog", deleterow);
function deleterow(){ 
	if (confirm("Are you sure want to delete service?")) {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host="<?php echo e(url('admin/services/deleted/')); ?>";
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
					window.location = "<?php echo e(url('admin/services')); ?>";
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
		if (confirm("Are you sure delete all the selected services?"))
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminmain', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>