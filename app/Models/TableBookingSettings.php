<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TableBookingSettings extends Model
{
	protected $table = 'table_booking_settings';
	
	public $timestamps = false;
	
	public $table_booking_id = 3; // get from category_type table
	 
	/*public function category_type()
    {
        return $this->belongsTo('App\Models\CategoryType');
    }*/
	
	public function getUpcomingSixDays()
	{
		$dt = Carbon::now();
		$days[1] = array(
							'day_format1' => (string) $dt->format('D d M'),
							'day_format2' => (string) $dt->format('Y-m-d')
							);
		for($i = 2; $i <= 6; $i++)
		{
			$add_day = $dt->addDay();
			$days[$i] = array(
							'day_format1' => (string) $add_day->format('D d M'),
							'day_format2' => (string) $add_day->format('Y-m-d')
							);
		}
		
		return $days;
	}
	
    public function get_adding_rules()
    {
    	$rules = [
    	'listing_id'	=> 'required',
    	'start_time'	=>'required',
    	'end_time'		=>'required',
    	'people_limit'	=>'required|numeric',
    	'time_interval'	=> 'required|numeric'
    	];

    	$messages = [
    	'listing_id.required'	=> 'Select listing',
    	'start_time.required'	=> 'Select start time',
    	'end_time.required'		=> 'Select end time',
    	'people_limit.required'	=> 'People limit required',
    	'time_interval.required'=> 'Time interval is required',
    	];

    	return array('rules' => $rules, 'messages' => $messages);
    }
	
	public function getListingTimes($settings)
	{
		$times = array();
		
		if($settings)
		{
			$start_time = $settings->start_time;
			$end_time = $settings->end_time;
			$time_interval = $settings->time_interval;
			
			$days = $this->getUpcomingSixDays();
			
			foreach($days as $day_i => $day)
			{
				
				$next_time_format = $start_time_format = date ('h:ia',strtotime($day['day_format2'].' '.$start_time));
				$next_time_check = date ('Y-m-d H:i',strtotime($day['day_format2'].' '.$start_time));//echo '<br />';
				
				$end_time_check = date ('Y-m-d H:i',strtotime($day['day_format2'].' '.$end_time));
				$end_time_format = date ('h:ia',strtotime($day['day_format2'].' '.$end_time));
				
				$current_time_check = '';
				if($day['day_format2'] == date ('Y-m-d')) {
					$current_time_check = date ('Y-m-d H:00', strtotime('+2 hours'));//echo '<br />';
				}
				
				
				for($i = 1; $i <= 24; $i++)
				{
					
					if($current_time_check != '' and $next_time_check <= $current_time_check and $next_time_check <= $end_time_check)
					{
						$times[$day_i][] = array('format' => $next_time_format, 'disable' => true);
					}
					else if($next_time_check <= $end_time_check)
					{
						$times[$day_i][] = array('format' => $next_time_format, 'disable' => false);
					}
					else
					{
						break;
					}
					
					// time interval to time
    				$next_time_format = date('h:ia', strtotime($next_time_format) + ($time_interval*60*60));
    				$next_time_check = date('Y-m-d H:i', strtotime($next_time_check) + ($time_interval*60*60));//echo '<br />';
    			}

    		}
    	}

		//echo '<pre>';print_r($times);echo '</pre>';exit;

    	return $times;
    }
    public function listing()
    {
    	return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
  
}
