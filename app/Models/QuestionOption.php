<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
     protected $table = 'question_options';
	 
	 public $timestamps = false;

	public function question_detail()
	{
		return $this->belongsTo('App\Models\QuestionDetail', 'question_detail_id');
	}
}
