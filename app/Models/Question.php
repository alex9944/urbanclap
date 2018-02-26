<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\QuestionDetail;
use App\Models\DynamicFields;
use App\Models\QuestionOption;

class Question extends Model
{
     protected $table = 'questions';
	 
	 public $timestamps = false;
	
	public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }

	public function question_details()
	{
		return $this->hasMany('App\Models\QuestionDetail', 'question_id');
	}
	
	public function standared_questions()
	{ // dynamic_field_id is fetch from table dynamic_fields 'text'
		$standared_questions = array(
									 	array('slug' => 'company', 'name' => 'Company Name', 'dynamic_field_id' => 1),
										array('slug' => 'first_name', 'name' => 'First Name', 'dynamic_field_id' => 1),
										array('slug' => 'last_name', 'name' => 'Last Name', 'dynamic_field_id' => 1),
										array('slug' => 'full_name', 'name' => 'Full Name', 'dynamic_field_id' => 1),
										array('slug' => 'email', 'name' => 'Email', 'dynamic_field_id' => 1),
										array('slug' => 'phone', 'name' => 'Phone Number', 'dynamic_field_id' => 1)
								);
		return $standared_questions;
	}
	
	private function _addQuestionDetailData($dynamic_fields, $question_id, $label, $field, $is_mandatory = 0, $is_date_picker = 0, $is_time_picker = 0)
	{
		$QuestionDetail = new QuestionDetail;		
		
		foreach($dynamic_fields as $dynamic_field)
		{
			if($dynamic_field->field_type == $field) {
				$QuestionDetail->dynamic_field_id = $dynamic_field->id;
				break;
			}
		}
		
		$QuestionDetail->question_id = $question_id;
		$QuestionDetail->label = $label;
		$QuestionDetail->is_mandatory = $is_mandatory;
		$QuestionDetail->is_date_picker = $is_date_picker;
		$QuestionDetail->is_time_picker = $is_time_picker;
		$QuestionDetail->save();
		
		return $QuestionDetail->id;
	}
	
	private function _addQuestionOption($question_detail_id, $option_value)
	{
		$QuestionOption = new QuestionOption;
		$QuestionOption->question_detail_id = $question_detail_id;
		$QuestionOption->option_value = $option_value;
		$QuestionOption->save();
	}
	
	public function addDynamicFields($question_id, $request)
	{		
		$standared_questions = $this->standared_questions();
		foreach($standared_questions as $question_arr)
		{
			$standared_qtn_status = 'standared_qtn_status_'.$question_arr['slug'];
			$standared_qtn_mandate = 'standared_qtn_mandate_'.$question_arr['slug'];
			if( $request->{$standared_qtn_status} == 'yes')
			{
				$QuestionDetail = new QuestionDetail;
				$QuestionDetail->label = $question_arr['name'];
				$QuestionDetail->dynamic_field_id = $question_arr['dynamic_field_id'];
				if( $request->{$standared_qtn_mandate} == 'yes')
					$QuestionDetail->is_mandatory = 1;
				
				$QuestionDetail->standard_field_slug = $question_arr['slug'];
				$QuestionDetail->question_id = $question_id;
				$QuestionDetail->save();
			}
		}
		
		$dynamic_fields = DynamicFields::where('status', 'Active')->get();
		
		$qtn_nos = $request->qes;
		if($qtn_nos)
		{
			foreach($qtn_nos as $qtn_no)
			{				
				$question_input = 'question_input'.$qtn_no;
				$question_input = $request->{$question_input};
				
				if($question_input != '')
				{
					if($question_input == 'text')
					{
						$label = $request->{'question_label_'.$question_input.'_'.$qtn_no};							
						$mandatory = ($request->{'mand'.$qtn_no} == 'yes') ? 1 : 0;
						$date_picker = ($request->{'date_picker_'.$qtn_no} == 'yes') ? 1 : 0;
						$time_picker = ($request->{'time_picker_'.$qtn_no} == 'yes') ? 1 : 0;
						
						$this->_addQuestionDetailData($dynamic_fields, $question_id, $label, $question_input, $mandatory, $date_picker, $time_picker);						
					}	
					else if($question_input == 'textarea')
					{						
						$label = $request->{'question_label_'.$question_input.'_'.$qtn_no};							
						$mandatory = ($request->{'mand'.$qtn_no} == 'yes') ? 1 : 0;		
						
						$this->_addQuestionDetailData($dynamic_fields, $question_id, $label, $question_input, $mandatory);						
					}
					else if($question_input == 'select' 
							|| $question_input == 'multiselect'
							|| $question_input == 'radio'
							|| $question_input == 'checkbox'
							)
					{						
						$label = $request->{'question_label_'.$question_input.'_'.$qtn_no};							
						$mandatory = ($request->{'mand'.$qtn_no} == 'yes') ? 1 : 0;		
						
						$question_detail_id = $this->_addQuestionDetailData($dynamic_fields, $question_id, $label, $question_input, $mandatory);
						
						$option_length = 5;
						for($i = 1; $i <= $option_length; $i++)
						{
							$option_value = $request->{$question_input.'_option'.$i.'_'.$qtn_no};
							
							// check if any one has value
							if($option_value != '') {
								$this->_addQuestionOption($question_detail_id, $option_value);
							}
						}
					}
					
				}			
			}
		}
	}	
	
	private function _updateQuestionDetailData($question_detail_id, $dynamic_fields, $question_id, $label, $field, $is_mandatory = 0, $is_date_picker = 0, $is_time_picker = 0)
	{
		$QuestionDetail_exists = QuestionDetail::find($question_detail_id);
		
		if($QuestionDetail_exists)
		{
			// delete options if field other than exists
			if($QuestionDetail_exists->dynamic_field_id != $field)
			{
				$QuestionDetail_exists->question_options()->delete();
			}
			
			foreach($dynamic_fields as $dynamic_field)
			{
				if($dynamic_field->field_type == $field) {
					$QuestionDetail_exists->dynamic_field_id = $dynamic_field->id;
					break;
				}
			}
			
			$QuestionDetail_exists->label = $label;
			$QuestionDetail_exists->is_mandatory = $is_mandatory;
			$QuestionDetail_exists->is_date_picker = $is_date_picker;
			$QuestionDetail_exists->is_time_picker = $is_time_picker;
			$QuestionDetail_exists->save();
			
			return $QuestionDetail_exists->id;
		}
		else
		{
			$QuestionDetail = new QuestionDetail;		
			
			foreach($dynamic_fields as $dynamic_field)
			{
				if($dynamic_field->field_type == $field) {
					$QuestionDetail->dynamic_field_id = $dynamic_field->id;
					break;
				}
			}
			
			$QuestionDetail->question_id = $question_id;
			$QuestionDetail->label = $label;
			$QuestionDetail->is_mandatory = $is_mandatory;
			$QuestionDetail->is_date_picker = $is_date_picker;
			$QuestionDetail->is_time_picker = $is_time_picker;
			$QuestionDetail->save();
			
			return $QuestionDetail->id;
		}
	}
	
	private function _updateQuestionOption($option_id, $question_detail_id, $option_value)
	{
		$QuestionOption_exists = QuestionOption::find($option_id);
		
		if($QuestionOption_exists)
		{
			if($option_value != '')
			{
				$QuestionOption_exists->option_value = $option_value;
				$QuestionOption_exists->save();
			}
			else
			{
				$QuestionOption_exists->delete();
			}
		}
		else		
		{
			$QuestionOption = new QuestionOption;
			$QuestionOption->question_detail_id = $question_detail_id;
			$QuestionOption->option_value = $option_value;
			$QuestionOption->save();
		}
	}
	
	public function updateDynamicFields($question_id, $request)
	{		
		$standared_questions = $this->standared_questions();
		foreach($standared_questions as $question_arr)
		{
			$standared_qtn_status = 'standared_qtn_status_'.$question_arr['slug'];
			$standared_qtn_mandate = 'standared_qtn_mandate_'.$question_arr['slug'];
			if( $request->{$standared_qtn_status} == 'yes')
			{
				$QuestionDetail_exists = QuestionDetail::where(['standard_field_slug' => $question_arr['slug'], 'question_id' => $question_id])->first();
				if($QuestionDetail_exists)
				{
					if( $request->{$standared_qtn_mandate} == 'yes')
						$QuestionDetail_exists->is_mandatory = 1;
					else
						$QuestionDetail_exists->is_mandatory = 0;
					
					$QuestionDetail_exists->save();
				}
				else
				{
					$QuestionDetail = new QuestionDetail;
					$QuestionDetail->label = $question_arr['name'];
					$QuestionDetail->dynamic_field_id = $question_arr['dynamic_field_id'];
					if( $request->{$standared_qtn_mandate} == 'yes')
						$QuestionDetail->is_mandatory = 1;
					
					$QuestionDetail->standard_field_slug = $question_arr['slug'];
					$QuestionDetail->question_id = $question_id;
					$QuestionDetail->save();
				}
			}
			else
			{
				$QuestionDetail_exists = QuestionDetail::where(['standard_field_slug' => $question_arr['slug'], 'question_id' => $question_id])->first();
				if($QuestionDetail_exists)
				{					
					$QuestionDetail_exists->delete();
				}
			}
		}
		
		$dynamic_fields = DynamicFields::where('status', 'Active')->get();
		
		$qtn_nos = $request->qes;
		if($qtn_nos)
		{
			foreach($qtn_nos as $qtn_no)
			{				
				$question_input = 'question_input'.$qtn_no;
				$question_input = $request->{$question_input};
				$edit_id = $request->{'edit_id_'.$qtn_no};
				
				if($question_input != '')
				{
					if($question_input == 'text')
					{
						$label = $request->{'question_label_'.$question_input.'_'.$qtn_no};							
						$mandatory = ($request->{'mand'.$qtn_no} == 'yes') ? 1 : 0;
						$date_picker = ($request->{'date_picker_'.$qtn_no} == 'yes') ? 1 : 0;
						$time_picker = ($request->{'time_picker_'.$qtn_no} == 'yes') ? 1 : 0;
						
						if($edit_id)
							$this->_updateQuestionDetailData($edit_id, $dynamic_fields, $question_id, $label, $question_input, $mandatory, $date_picker, $time_picker);
						else
							$this->_addQuestionDetailData($dynamic_fields, $question_id, $label, $question_input, $mandatory, $date_picker, $time_picker);						
					}	
					else if($question_input == 'textarea')
					{						
						$label = $request->{'question_label_'.$question_input.'_'.$qtn_no};							
						$mandatory = ($request->{'mand'.$qtn_no} == 'yes') ? 1 : 0;		
						
						if($edit_id)
							$this->_updateQuestionDetailData($edit_id, $dynamic_fields, $question_id, $label, $question_input, $mandatory);						
						else
							$this->_addQuestionDetailData($dynamic_fields, $question_id, $label, $question_input, $mandatory);
					}
					else if($question_input == 'select' 
							|| $question_input == 'multiselect'
							|| $question_input == 'radio'
							|| $question_input == 'checkbox'
							)
					{						
						$label = $request->{'question_label_'.$question_input.'_'.$qtn_no};							
						$mandatory = ($request->{'mand'.$qtn_no} == 'yes') ? 1 : 0;		
						
						if($edit_id)
							$question_detail_id = $this->_updateQuestionDetailData($edit_id, $dynamic_fields, $question_id, $label, $question_input, $mandatory);
						else
							$question_detail_id = $this->_addQuestionDetailData($dynamic_fields, $question_id, $label, $question_input, $mandatory);
						
						$option_length = 5;
						for($i = 1; $i <= $option_length; $i++)
						{
							$option_value = $request->{$question_input.'_option'.$i.'_'.$qtn_no};
							$option_id = $request->{'qes_options_'.$qtn_no.'_'.($i-1)};
							
							if($option_id)
								$this->_updateQuestionOption($option_id, $question_detail_id, $option_value);
							else if($option_value != '')
								$this->_addQuestionOption($question_detail_id, $option_value);
						}
					}
					
				}			
			}
		}
	}
	
	public function validateDynamicFields($request)
	{
		$return = array('status' => 0, 'msg' => 'Use any one standared question');
		
		// vaidate satandared questions
		$standared_questions = $this->standared_questions();
		$standared_validation = false;
		foreach($standared_questions as $question_arr)
		{
			$standared_qtn_status = 'standared_qtn_status_'.$question_arr['slug'];
			if( $request->{$standared_qtn_status} == 'yes')
			{
				$standared_validation = true; // valid pass
				break;
			}
		}
		
		// is valid
		if($standared_validation)
		{
			$return['status'] = 1;
			$return['msg'] = '';
		}
		
		// is not vaid then check dynamic fields
		$qtn_nos = $request->qes;
		$msg_sep = "\n";
		if($qtn_nos)
		{
			if(!$standared_validation) {
				$return['status'] = 1; // set as default
			}
			foreach($qtn_nos as $qtn_no)
			{
				$input_valid_response = $this->_validateInputField($request, $qtn_no);
				
				if($input_valid_response['status'] == 0)
				{
					$return['status'] = 0;
					$return['msg'] .= $msg_sep . $input_valid_response['msg'];					
				}
			}
		}
		
		return $return;
	}
	
	private function _validateInputField($request, $qtn_no)
	{
		$question_input = 'question_input'.$qtn_no;
		$question_input = $request->{$question_input};
		
		$return = array('status' => 0, 'msg' => 'Select question '.$qtn_no);
		
		if($question_input != '')
		{
			if($question_input == 'text')
			{
				
				$question_label = 'question_label_text_'.$qtn_no;
				$question_label = $request->{$question_label};
				
				if($question_label == '')
					$return['msg'] = 'Select question '.$qtn_no. ' label';
				else
					$return['status'] = 1;
			}	
			else if($question_input == 'textarea')
			{
				
				$question_label = 'question_label_textarea_'.$qtn_no;
				$question_label = $request->{$question_label};
				
				if($question_label == '')
					$return['msg'] = 'Select question '.$qtn_no. ' label';
				else
					$return['status'] = 1;
			}
			else if($question_input == 'select')
			{
				
				$question_label = 'question_label_select_'.$qtn_no;
				$question_label = $request->{$question_label};
				
				if($question_label == '') {
					$return['msg'] = 'Select question '.$qtn_no. ' label';
				} else {
					$option_length = 5;
					for($i = 1; $i <= $option_length; $i++)
					{
						$select_option = 'select_option'.$i.'_'.$qtn_no;
						$select_option = $request->{$select_option};
						
						// check if any one has value
						if($select_option != '')
							$return['status'] = 1;
					}
					
					if($return['status'] == 0)
						$return['msg'] = 'Select any one option for the question '.$qtn_no;
				}
			}
			else if($question_input == 'multiselect')
			{
				
				$question_label = 'question_label_multiselect_'.$qtn_no;
				$question_label = $request->{$question_label};
				
				if($question_label == '') {
					$return['msg'] = 'Select question '.$qtn_no. ' label';
				} else {
					$option_length = 5;
					for($i = 1; $i <= $option_length; $i++)
					{
						$select_option = 'multiselect_option'.$i.'_'.$qtn_no;
						$select_option = $request->{$select_option};
						
						// check if any one has value
						if($select_option != '')
							$return['status'] = 1;
					}
					
					if($return['status'] == 0)
						$return['msg'] = 'Select any one option for the question '.$qtn_no;
				}
			}
			else if($question_input == 'radio')
			{
				
				$question_label = 'question_label_radio_'.$qtn_no;
				$question_label = $request->{$question_label};
				
				if($question_label == '') {
					$return['msg'] = 'Select question '.$qtn_no. ' label';
				} else {
					$option_length = 5;
					for($i = 1; $i <= $option_length; $i++)
					{
						$select_option = 'radio_option'.$i.'_'.$qtn_no;
						$select_option = $request->{$select_option};
						
						// check if any one has value
						if($select_option != '')
							$return['status'] = 1;
					}
					
					if($return['status'] == 0)
						$return['msg'] = 'Select any one option for the question '.$qtn_no;
				}
			}
			else if($question_input == 'checkbox')
			{
				
				$question_label = 'question_label_checkbox_'.$qtn_no;
				$question_label = $request->{$question_label};
				
				if($question_label == '') {
					$return['msg'] = 'Select question '.$qtn_no. ' label';
				} else {
					$option_length = 5;
					for($i = 1; $i <= $option_length; $i++)
					{
						$select_option = 'checkbox_option'.$i.'_'.$qtn_no;
						$select_option = $request->{$select_option};
						
						// check if any one has value
						if($select_option != '')
							$return['status'] = 1;
					}
					
					if($return['status'] == 0)
						$return['msg'] = 'Select any one option for the question '.$qtn_no;
				}
			}
			
		}
		
		return $return;
	}
}
