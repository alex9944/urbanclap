<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionDetail extends Model
{
     protected $table = 'question_details';
	 
	 public $timestamps = false;

	public function question()
	{
		return $this->belongsTo('App\Models\Question', 'question_id');
	}

	public function dynamic_field()
	{
		return $this->belongsTo('App\Models\DynamicFields', 'dynamic_field_id');
	}

	public function question_options()
	{
		return $this->hasMany('App\Models\QuestionOption', 'question_detail_id');
	}
}
