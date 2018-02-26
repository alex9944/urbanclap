<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Questions</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="<?php echo e(url('admin/questions/destroy')); ?>" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Questions <span class="pull-right"><a href="<?php echo e(url('admin/questions')); ?>" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Question Id</th>
                          <th>Service Id</th>
                          <th>Service name</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					
					<?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="rm<?php echo e($question->id); ?>">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="<?php echo e($question->id); ?>"/>				 	  
						  </td>
                          <td><?php echo e($question->id); ?></td>
                          <td><?php echo e($question->service->id); ?></td>
                          <td><?php echo e($question->service->name); ?></td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" data-id="<?php echo e($question->id); ?>" ><i class="fa fa-pencil"></i></a> 
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" data-id="<?php echo e($question->id); ?>"><i class="fa fa-trash-o"></i> </a></td>
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
						$url = url('admin/questions/added');
						if(old('id') != '')
						{
							$id = old('id');
							$add = 'Edit';
							$url = url('admin/questions/updated');
						}
						?>
                        
                        <h2><span id="add_div_label"><?php echo e($add); ?></span> Questions </h2>
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
                                    <label class="col-sm-2 control-label"> Service</label>
                                    <div class="col-sm-10">
                                        <select name="service_id" id="service_id"  class="form-control"> 
                                            <option value="">---Choose---</option>
                                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($service->id); ?>" <?php if( old('service_id') == $service->id ): ?> selected <?php endif; ?>>
                                                <?php echo e($service->name); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>	
                                    </div>
                                </div>
                                
                                <div class="form-group ">
                                    <label class="col-sm-2 control-label"> Standard Question</label>
                                    <div class="col-sm-10">
                                
                                        <table class="table table-striped table-bordered">
                                              <tr>
                                              	<th></th>
                                              	<th>Use the field</th>
                                              	<th>Mandatory fields</th>
                                              </tr>
                                              <?php $__currentLoopData = $standared_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question_arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <tr style="">
                                                 <td><?php echo e($question_arr['name']); ?></td>
                                                 <td><input type="radio" id="st_qn_st_yes_<?php echo e($question_arr['slug']); ?>" name="standared_qtn_status_<?php echo e($question_arr['slug']); ?>" value="yes">Yes <input type="radio" name="standared_qtn_status_<?php echo e($question_arr['slug']); ?>" value="no" checked>No</td>
                                                 <td><input type="radio" id="st_qn_ma_yes_<?php echo e($question_arr['slug']); ?>" value="yes" name="standared_qtn_mandate_<?php echo e($question_arr['slug']); ?>">Yes &nbsp;&nbsp;<input type="radio" value="no" name="standared_qtn_mandate_<?php echo e($question_arr['slug']); ?>" checked>No</td>
                                              </tr>
                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </table>
                                     </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <div class="input_fields_wrap_edit">
                                            
                                        </div>
                                	</div>
                                </div>
                                
                                <div class="form-group ">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <div class="input_fields_wrap">
                                            <button class="add_field_button btn btn-primary">Add More Fields</button>
                                        </div>
                                	</div>
                                </div>
                                
                                
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10">
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
    
<script type="text/javascript">
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
	var wrapper_edit         = $(".input_fields_wrap_edit"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
	var s1x=0+1;

    //var s1x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(s1x < max_fields){ //max input box allowed
           	
			/*var divbox = '<div>'
			+'<table width="100%"  style="border:1px solid#ccc;">'
			+'<tbody>'
			+'<tr><td ><table width="100%" border="0"><tbody><tr><td>Question '+ s1x +'</td><tbody><tr></table><table width="100%" border="0"><tbody><tr><td><input type="hidden" name="qes[]" value="'+ s1x +'"><select name="slider'+ s1x +'" onchange="javascript:sss(this.value,'+ s1x +');"><option value="">Select a input type:</option><option value="text">Text only</option><option value="country">Country</option><option value="radio">Single Select</option><option value="check_box">Mulitple Select</option><option value="dropdown">Dropdown</option><option value="text_box">Text Box</option><option value="text_area">Text Area</option></select><input type="hidden" name="question_sno'+ s1x +'" value="'+ s1x +'"></td><td><table width="100%"  style="border:1px solid#ccc;"><tbody><tr><td>Mandatory fields</td><td><input type="radio" value="1" name="mand'+ s1x +'">Yes &nbsp;&nbsp;<input type="radio" value="2" name="mand'+ s1x +'" checked>No</td></tr></tbody></table></td></tr></tbody></table><table width="100%" border="0" style="border:0px;"><tbody><tr><td style="border:0px;"><div id="s1_q1_'+ s1x +'"></div></td><tbody><tr></table></td><tbody><tr></table><a href="#" class="remove_field">Remove</a></div><br />';*/
			
			$('.remove_button').remove();
			
			var divbox = '<div class="addmore_field_section_'+ s1x +'" style="margin-top:10px;">'
						   +'<table class="table" width="100%"  style="border:1px solid#ccc;">'
								 +'<tr>'
									+'<td >Question '+ s1x +'</td>'
								 +'</tr>'
								 +'<tr>'
									+'<td>'
										+'<div class="col-sm-5">'
											+'<input type="hidden" name="qes[]" value="'+ s1x +'">'
											+'<select class="question_input form-control" name="question_input'+ s1x +'" data-sno="'+ s1x +'">'
											  +'<option value="">Select a input type:</option>'
											  <?php $__currentLoopData = $dynamic_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dynamic_field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											  +'<option value="<?php echo e($dynamic_field->field_type); ?>"><?php echo ucfirst($dynamic_field->field_type);?></option>'
											  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											+'</select>'
											+'<input type="hidden" name="question_sno'+ s1x +'" value="'+ s1x +'">'
										+'</div>'
										+'<div class="col-sm-3">Mandatory fields</div>'
										+'<div class="col-sm-4"><input type="radio" value="yes" name="mand'+ s1x +'">Yes &nbsp;&nbsp;<input type="radio" value="no" name="mand'+ s1x +'" checked>No</div>'
									+'</td>'
								 +'</tr>'
								 +'<tr>'
									+'<td style="border:0px;">'
									   +'<div id="s1_q1_'+ s1x +'"></div>'
									+'</td>'
								 +'</tr>'
								 +'<tr class="remove_button">'
									+'<td ><button type="button" class="remove_field btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Remove</button></td>'
								 +'</tr>'
						   +'</table>  ' 
						+'</div>';
			
			 $(wrapper).append(divbox);
			  s1x++; //text box increment
           
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        //var id = $(this).data('id');
		s1x--;
		e.preventDefault(); 
		$('div.addmore_field_section_'+s1x).remove(); 
		
		var remove_button = '<tr class="remove_button">'
									+'<td ><button type="button" class="remove_field btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Remove</button></td>'
								 +'</tr>';
		
		if($('div.addmore_field_section_'+(s1x-1)).length > 0)
		{
			$(remove_button).insertAfter($('div.addmore_field_section_'+(s1x-1)+' table tr').last());
		}
    });
	
	$(wrapper).on("change",".question_input", function(e){
		
		var str = $(this).val();
		var i = $(this).data('sno');
		
		qtn_input_fields(str, i, 'add', '');
	});
	
	function qtn_input_fields(str, i, type, question_detail)
	{
		var question_label = '';
		var option_html = '';
		var date_picker_yes = '';
		var time_picker_yes = '';
		var date_picker_no = ' checked';
		var time_picker_no = ' checked';
		var option = new Array('', '', '', '', '');
		
		if(type == 'edit')
		{
			question_label = question_detail.label;
			question_options = question_detail.question_options;
			
			if(question_detail.is_date_picker) {
				date_picker_yes = ' checked';
				date_picker_no = '';
			}
			if(question_detail.is_time_picker) {
				time_picker_yes = ' checked';
				time_picker_no = '';
			}
			
			// option
			var q_i = 0;			
			$.each(question_options, function(index, data) {
				option[q_i] = data.option_value;				
				option_html += '<input type="hidden" name="qes_options_'+i+'_'+q_i+'" value="'+ data.id +'">';
				q_i++;
			});
		}
		
		if (str == "") {
			$("#s1_q1_"+i).html('');
			//return;
		} else { 
			
			var input_html = '';
			if(str == 'text')
			{
				input_html += '<div class="col-sm-6">Question Label</div>'
							+ '<div class="col-sm-6"><input type="text" name="question_label_text_'+i+'" value="'+question_label+'"></div>'
							+ '<div class="col-sm-6">Date picker</div>'
							+ '<div class="col-sm-6"><input type="radio" value="yes" name="date_picker_'+ i +'"'+date_picker_yes+'>Yes &nbsp;&nbsp;<input type="radio" value="no" name="date_picker_'+ i +'"'+date_picker_no+'>No</div>'
							+ '<div class="col-sm-6">Time picker</div>'
							+ '<div class="col-sm-6"><input type="radio" value="yes" name="time_picker_'+ i +'"'+time_picker_yes+'>Yes &nbsp;&nbsp;<input type="radio" value="no" name="time_picker_'+ i +'"'+time_picker_no+'>No</div>'
							;
			}
			else if(str == 'textarea')
			{
				input_html += '<div class="col-sm-6">Question Label</div>'
							+ '<div class="col-sm-6"><input type="text" name="question_label_textarea_'+i+'" value="'+question_label+'"></div>';
			}
			else if(str == 'select')
			{
				input_html += '<div class="col-sm-6">Question Label</div>'
							+ '<div class="col-sm-6"><input type="text" name="question_label_select_'+i+'" value="'+question_label+'"></div>'
							
							+ '<div class="col-sm-12" style="margin-bottom:10px;"><b>Select attributes</b></div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 1</div>'
							+ '<div class="col-sm-6"><input type="text" name="select_option1_'+i+'" value="'+option[0]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 2</div>'
							+ '<div class="col-sm-6"><input type="text" name="select_option2_'+i+'" value="'+option[1]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 3</div>'
							+ '<div class="col-sm-6"><input type="text" name="select_option3_'+i+'" value="'+option[2]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 4</div>'
							+ '<div class="col-sm-6"><input type="text" name="select_option4_'+i+'" value="'+option[3]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 5</div>'
							+ '<div class="col-sm-6"><input type="text" name="select_option5_'+i+'" value="'+option[4]+'"></div>'
							+ '</div>'
							;
			}
			else if(str == 'multiselect')
			{
				input_html += '<div class="col-sm-6">Question Label</div>'
							+ '<div class="col-sm-6"><input type="text" name="question_label_multiselect_'+i+'" value="'+question_label+'"></div>'
							
							+ '<div class="col-sm-12" style="margin-bottom:10px;"><b>Multiselect attributes</b></div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 1</div>'
							+ '<div class="col-sm-6"><input type="text" name="multiselect_option1_'+i+'" value="'+option[0]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 2</div>'
							+ '<div class="col-sm-6"><input type="text" name="multiselect_option2_'+i+'" value="'+option[1]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 3</div>'
							+ '<div class="col-sm-6"><input type="text" name="multiselect_option3_'+i+'" value="'+option[2]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 4</div>'
							+ '<div class="col-sm-6"><input type="text" name="multiselect_option4_'+i+'" value="'+option[3]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 5</div>'
							+ '<div class="col-sm-6"><input type="text" name="multiselect_option5_'+i+'" value="'+option[4]+'"></div>'
							+ '</div>'
							;
			}
			else if(str == 'radio')
			{
				input_html += '<div class="col-sm-6">Question Label</div>'
							+ '<div class="col-sm-6"><input type="text" name="question_label_radio_'+i+'" value="'+question_label+'"></div>'
							
							+ '<div class="col-sm-12" style="margin-bottom:10px;"><b>Radio attributes</b></div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 1</div>'
							+ '<div class="col-sm-6"><input type="text" name="radio_option1_'+i+'" value="'+option[0]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 2</div>'
							+ '<div class="col-sm-6"><input type="text" name="radio_option2_'+i+'" value="'+option[1]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 3</div>'
							+ '<div class="col-sm-6"><input type="text" name="radio_option3_'+i+'" value="'+option[2]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 4</div>'
							+ '<div class="col-sm-6"><input type="text" name="radio_option4_'+i+'" value="'+option[3]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 5</div>'
							+ '<div class="col-sm-6"><input type="text" name="radio_option5_'+i+'" value="'+option[4]+'"></div>'
							+ '</div>'
							;
			}
			else if(str == 'checkbox')
			{
				input_html += '<div class="col-sm-6">Question Label</div>'
							+ '<div class="col-sm-6"><input type="text" name="question_label_checkbox_'+i+'" value="'+question_label+'"></div>'
							
							+ '<div class="col-sm-12" style="margin-bottom:10px;"><b>Checkbox attributes</b></div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 1</div>'
							+ '<div class="col-sm-6"><input type="text" name="checkbox_option1_'+i+'" value="'+option[0]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 2</div>'
							+ '<div class="col-sm-6"><input type="text" name="checkbox_option2_'+i+'" value="'+option[1]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 3</div>'
							+ '<div class="col-sm-6"><input type="text" name="checkbox_option3_'+i+'" value="'+option[2]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 4</div>'
							+ '<div class="col-sm-6"><input type="text" name="checkbox_option4_'+i+'" value="'+option[3]+'"></div>'
							+ '</div>'
							+ '<div class="form-group"><div class="col-sm-6">Option 5</div>'
							+ '<div class="col-sm-6"><input type="text" name="checkbox_option5_'+i+'" value="'+option[4]+'"></div>'
							+ '</div>'
							;
			}
			
			$("#s1_q1_"+i).html(input_html+option_html);
		}
	}
	
	// form submit
	$("#Servicefrm").submit(function(e) {

		var url = $(this).attr('action');
	
		$.ajax({
				type: "POST",
				url: url,
				data: $("#Servicefrm").serialize(), // serializes the form's elements.
				dataType: "json", // data type of response		
				beforeSend: function(){
				$('.image_loader').show();
				},
				complete: function(){
				$('.image_loader').hide();
				},
				success: function(res)
				{
					if(res.status)
					{
						window.location = "<?php echo e(url('admin/questions')); ?>";
					}
					else
					{
						alert(res.msg);
					}
				}
			 });
	
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	// EDit Blog
	$(document).on("click", ".edit_blog", edit_row);
	function edit_row()
	{ 
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).data('id'); 
		
		$(wrapper_edit).html('');
		
		var url = "<?php echo e(url('admin/questions/updated')); ?>";
		$('#Servicefrm').attr('action', url);
	
		var host="<?php echo e(url('admin/questions/get')); ?>";
		
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
		var sel_question = res.view_details.question;
		var question_details = sel_question.question_details;
		var standared_questions = res.view_details.standared_questions;
		
		$('#id').val(sel_question.id);	
		$('#service_id').val(sel_question.service_id);	
		$('#status').val(sel_question.status);
		
		// dynamic fields input_fields_wrap_edit
		$.each(question_details, function(index, data) {			
			
			if(data.standard_field_slug) {
				$('#st_qn_st_yes_'+data.standard_field_slug).prop('checked', true);
				if(data.is_mandatory)
					$('#st_qn_ma_yes_'+data.standard_field_slug).prop('checked', true);
				return true;
			}
			
			var dynamic_field = data.dynamic_field;
			var divbox = '<div class="edit_addmore_field_section_'+ s1x +'" style="margin-top:10px;">'
						   +'<table class="table" width="100%"  style="border:1px solid#ccc;">'
								 +'<tr>'
									+'<td >Question '+ s1x +'</td>'
								 +'</tr>'
								 +'<tr>'
									+'<td>'
										+'<div class="col-sm-5">'
											+'<input type="hidden" name="qes[]" value="'+ s1x +'">'
											+'<input type="hidden" name="edit_id_'+ s1x +'" value="'+ data.id +'">'
											+'<select class="question_input form-control" name="question_input'+ s1x +'" data-sno="'+ s1x +'">'
											  +'<option value="">Select a input type:</option>';
											  <?php $__currentLoopData = $dynamic_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dynamic_field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											  
											  var selected = '';
											  if(data.dynamic_field_id == <?php echo e($dynamic_field->id); ?>)
											  	selected = ' selected';
												
											  divbox += '<option value="<?php echo e($dynamic_field->field_type); ?>"'+selected+'><?php echo ucfirst($dynamic_field->field_type);?></option>'
											  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											+'</select>'
										+'</div>'
										+'<div class="col-sm-3">Mandatory fields</div>';
										
										var checked_yes = '';
										var checked_no = ' checked';
										if(data.is_mandatory) {
											checked_yes = ' checked';
											checked_no = '';
										}
										
										divbox +='<div class="col-sm-4"><input type="radio" value="yes" name="mand'+ s1x +'"'+checked_yes+'>Yes &nbsp;&nbsp;<input type="radio" value="no" name="mand'+ s1x +'"'+checked_no+'>No</div>'
									+'</td>'
								 +'</tr>'
								 +'<tr>'
									+'<td style="border:0px;">'
									   +'<div id="s1_q1_'+ s1x +'"></div>'
									+'</td>'
								 +'</tr>'
								 +'<tr class="edit_remove_button">'
									+'<td ><button type="button" class="edit_remove_field btn btn-danger btn-xs" data-s1x="'+ s1x +'" data-id="'+ data.id +'"><i class="fa fa-trash-o"></i> Remove</button></td>'
								 +'</tr>'
						   +'</table>  ' 
						+'</div>';
			
			 $(wrapper_edit).append(divbox);
			 
			 qtn_input_fields(dynamic_field.field_type, s1x, 'edit', data);
			 
			 s1x++;
		});
	}
	
	$(document).on("click", ".edit_remove_field", edit_remove_field);
	function edit_remove_field()
	{ 
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id = $(this).data('id'); 
		var edit_s1x = $(this).data('s1x'); 
	
		var host="<?php echo e(url('admin/questions/question_detail_deleted')); ?>";
		
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
			success: function (res)
			{
				if(res.status == 1)
				{
					$('div.edit_addmore_field_section_'+edit_s1x).remove();
				}
				alert(res.msg);
			}
		
		})
		return false;
	}
	
});
</script>

<script>

$(document).on("click", ".delete_blog", deleterow);
function deleterow(){ 
	if (confirm("Are you sure want to delete question?")) {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).data('id'); 
		var host="<?php echo e(url('admin/questions/deleted/')); ?>";
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
					window.location = "<?php echo e(url('admin/questions')); ?>";
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
		if (confirm("Are you sure delete all the selected question?"))
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