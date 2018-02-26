<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Service;
use App\Models\Question;
use App\Models\DynamicFields;
use App\Models\QuestionDetail;

use Image;
use Validator;


class QuestionController extends Controller
{
    //
	public function index()
    {		
	  	$services = Service::all();
		$questions = Question::all();		
		$dynamic_fields = DynamicFields::where('status', 'Active')->get();
		
		$Question = new Question;
		$standared_questions = $Question->standared_questions();
	  
       	return view('panels.admin.questions.index', compact('services', 'questions', 'standared_questions', 'dynamic_fields'));
    }
	
	public function get(Request $request)
	{
		$question = Question::with('question_details.question_options', 'question_details.dynamic_field')->find($request->id); 
		$question_details = $question->question_details;
		
		$Question = new Question;
		$standared_questions = $Question->standared_questions();
		$data['question'] = $question;
		$data['standared_questions'] = $standared_questions;
		
		return '{"view_details": ' . json_encode($data) . '}';
	}
	
	private function _validationErrorsToString($errArray) {
        $valArr = array();
        foreach ($errArray->toArray() as $key => $value) { 
            $errStr = $value[0];
            array_push($valArr, $errStr);
        }
        if(!empty($valArr)){
            $errStrFinal = implode(',', $valArr);
        }
        return $errStrFinal;
    }
	
	public function added(Request $request)
	{		
		$return = array('status' => 0, 'msg' => 'Invaid request');
		
		$rules = [
		    'service_id'            	=> 'required|unique:questions',
		];		
		$messages = [
		    'service_id.required'   	=> 'Select service', 
			'service_id.unique'   		=> 'Service already exists', 
			
		];
		
		$validator = \Validator::make($request->all(), $rules, $messages);
				
		if ($validator->fails()) {
			//$return['data'] = $validator->errors()->toArray();
			$return['msg'] = $this->_validationErrorsToString($validator->errors());//'Select service';
			return response()->json($return);
		}
		
		$Question = new Question;
		
		$validate_response = $Question->validateDynamicFields($request);
		
		if($validate_response['status'] == 0)
		{
			$return['msg'] = $validate_response['msg'];
			return response()->json($return);
		}
		
		$Question->service_id = $request->service_id;
		$Question->status = $request->status;
		$Question->save();
		
		$Question->addDynamicFields($Question->id, $request);
		
		\Session::flash('message', 'Question added successfully.');
		$return['status'] = 1;
		$return['msg'] = 'Question added successfully';
		return response()->json($return);
	}
	
	public function updated(Request $request)
	{	 
		$return = array('status' => 0, 'msg' => 'Invaid request');
		
		$question_id = $request->id;
		
		if($question_id)
		{
			$Question = Question::find($question_id);
			
			if($Question)
			{		
				$rules = [
					'service_id'            	=> 'required|unique:questions,service_id,'.$Question->id,
				];		
				$messages = [
					'service_id.required'   	=> 'Select service', 
					'service_id.unique'   		=> 'Service already exists', 
					
				];
				
				$validator = \Validator::make($request->all(), $rules, $messages);
						
				if ($validator->fails()) {
					//$return['data'] = $validator->errors()->toArray();
					$return['msg'] = $this->_validationErrorsToString($validator->errors());
					return response()->json($return);
				}
				
				$validate_response = $Question->validateDynamicFields($request);
				
				if($validate_response['status'] == 0)
				{
					$return['msg'] = $validate_response['msg'];
					return response()->json($return);
				}
				
				// validation ok
				
				$Question->service_id = $request->service_id;
				$Question->status = $request->status;
				$Question->save();
				
				$Question->updateDynamicFields($Question->id, $request);
				
				\Session::flash('message', 'Question updated successfully.');
				$return['status'] = 1;
				$return['msg'] = 'Question updated successfully';
				return response()->json($return);	
			}
		}
		
		return response()->json($return);
	}
	
	public function question_detail_deleted(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Invaid Id');
		
		$id = $request->id;
		
		$QuestionDetail = QuestionDetail::find($id);
		
		if($QuestionDetail)
		{
			$QuestionDetail->question_options()->delete();
			$is_deleted = $QuestionDetail->delete();
			
			if($is_deleted)
			{
				$return['status'] = 1;
				$return['msg'] = 'Question detail deleted successfully';
			}
			else
			{
				$return['msg'] = 'Question detail not deleted. Related leads exists.';
			}
		}
		
		return response()->json($return);
	}

	public function deleted(Request $request)
	{	 
		$id=$request->id;  
		$success_msg = 'Seltected row deleted successfully.';
		
		return $this->_delete($id, $success_msg); 
	
	}
	
	private function _delete($id, $success_msg, $is_continue = false)
	{
		$Question = Question::find($id);
		
		$error_msg = '';
		
		if(!$is_continue)
		{
			if($Question)
			{
				foreach($Question->question_details as $question_detail)
				{
					$question_detail->question_options()->delete();
				}
				$Question->question_details()->delete();
				$Question->delete();
				
				\Session::flash('message', $success_msg);
					
				return \Response::json(array(
					'success' => true,
					'msg'   => $success_msg
				));
			}
			return \Response::json(array(
					'success' => false,
					'msg'   => 'Invaid Id'
				));
			
		}
		else if(!$error_msg)
		{
			foreach($Question->question_details as $question_detail)
			{
				$question_detail->question_options()->delete();
			}
			$Question->question_details->delete();
			$Question->delete();
		}
		
		return $error_msg;
	}

	public function destroy(Request $request)
	{
		$msg = 'Seltected category are deleted successfully';
		$error_msg = '';
		
		$cn=count($request->selected_id);
		if($cn>0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				
				$return_msg = $this->_delete($id, $msg, true);
				
				if($return_msg)
					$error_msg = $return_msg;
			}			
		} 
		
		if($error_msg)
			return redirect('admin/category')->with('error_message', $error_msg);
		
		return redirect('admin/category')->with('message', $msg);			

	}
}