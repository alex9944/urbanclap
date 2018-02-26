<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\MerchantOrder;
use DateTime;
use App\Http\Controllers\Controller;
use Image;



class ReportsController extends Controller
{
    
	public function onlineorder(Request $request)
    {		
	
	    
	    if(!isset($request->filter_date_start)) {
			$data['filter_date_start'] = (new DateTime(date('Y-m-d')))
												->modify('first day of this month')
												->format('Y-m-d');			
		} else {
			$data['filter_date_start']=date('Y-m-d', strtotime($request->filter_date_start));
		}
		
		if(!isset($request->filter_date_end)) {
			$data['filter_date_end'] = date('Y-m-d');
		} else {
			$data['filter_date_end']=date('Y-m-d', strtotime($request->filter_date_end));
		}	
	     
	    $start_date = date( 'Y-m-d', strtotime( $data['filter_date_start'] ) );
		$end_date = date( 'Y-m-d', strtotime( $data['filter_date_end'] ) );
		
		$status_where = '';
		$data['filter_merchant_orders_order_status']= '';
		if(isset($request->filter_merchant_orders_order_status) and $data['filter_merchant_orders_order_status'])
			$status_where = 'AND status = \''.$data['filter_merchant_orders_order_status'].'\' ';
		
	  
		if(!isset($request->filter_group))
			$data['filter_group'] = 'day';
		else
			$data['filter_group'] = $request->filter_group;
		
		switch($data['filter_group'])
		{
			case 'year':
			               
						$sql = 'SELECT YEAR(merchant_orders.created_at) AS year_for, '.
								'MAKEDATE(YEAR(merchant_orders.created_at),01) AS start_date, '.
								'LAST_DAY(DATE_ADD(merchant_orders.created_at, INTERVAL 12-MONTH(merchant_orders.created_at) MONTH)) AS end_date, '.
								'COUNT(merchant_orders.total_items) AS total_items, '.
								'SUM(merchant_orders.site_commission_amount) AS site_commission_amount, '.
								'SUM(merchant_orders.merchant_net_amount) AS merchant_net_amount, '.
								'SUM(merchant_orders.total_amount) AS total_amount '.
								'FROM merchant_orders '.
								'WHERE DATE(merchant_orders.created_at) >= \''.$start_date.'\' AND DATE(merchant_orders.created_at) <= \''.$end_date.'\' '.$status_where.
								'GROUP BY YEAR(merchant_orders.created_at) '.
								'ORDER BY year_for'
								;
						break;
			case 'month':
						$sql = 'SELECT MONTH(merchant_orders.created_at) AS month_for, '.
			                    'DATE(DATE_ADD(DATE_ADD(LAST_DAY(merchant_orders.created_at),INTERVAL 1 DAY),INTERVAL -1 MONTH)) AS start_date, '.
								'LAST_DAY(merchant_orders.created_at) AS end_date, '.
								'COUNT(merchant_orders.total_items) AS total_items, '.
								'SUM(merchant_orders.site_commission_amount) AS site_commission_amount, '.
								'SUM(merchant_orders.merchant_net_amount) AS merchant_net_amount, '.
								'SUM(merchant_orders.total_amount) AS total_amount '.
								'FROM merchant_orders '.
								'WHERE DATE(merchant_orders.created_at) >= \''.$start_date.'\' AND DATE(merchant_orders.created_at) <= \''.$end_date.'\' '.$status_where.
								'GROUP BY MONTH(merchant_orders.created_at) '.
								'ORDER BY month_for'
								;
						break;
			case 'week':
						$sql = 'SELECT WEEK(merchant_orders.created_at) AS week_for, '.
								//'DATE_SUB('.
								//  'DATE_ADD(MAKEDATE(YEAR(order_summary.ord_date), 1), INTERVAL WEEK(order_summary.ord_date) WEEK),'.
								//  'INTERVAL WEEKDAY('.
								//	'DATE_ADD(MAKEDATE(YEAR(order_summary.ord_date), 1), INTERVAL WEEK(order_summary.ord_date) WEEK)'.
								//  ') -1 DAY) AS Week_date, '.
								'DATE(DATE_ADD(merchant_orders.created_at, INTERVAL(1-DAYOFWEEK(merchant_orders.created_at)) DAY)) AS start_date, '.
								'DATE(DATE_ADD(merchant_orders.created_at, INTERVAL(7-DAYOFWEEK(merchant_orders.created_at)) DAY)) AS end_date, '.
								'COUNT(merchant_orders.total_items) AS total_items, '.
								'SUM(merchant_orders.site_commission_amount) AS site_commission_amount, '.
								'SUM(merchant_orders.merchant_net_amount) AS merchant_net_amount, '.
								'SUM(merchant_orders.total_amount) AS total_amount '.
								'FROM merchant_orders '.
								'WHERE DATE(merchant_orders.created_at) >= \''.$start_date.'\' AND DATE(merchant_orders.created_at) <= \''.$end_date.'\' '.$status_where.
								'GROUP BY WEEK(merchant_orders.created_at)'.
								'ORDER BY week_for'
								;
						break; 
			case 'day':
						$sql = 'SELECT DAY(merchant_orders.created_at) AS day_for, '.
								'DATE(merchant_orders.created_at) AS start_date, '.
								'DATE(merchant_orders.created_at) AS end_date, '.
								'COUNT(merchant_orders.total_items) AS total_items, '.
								'SUM(merchant_orders.site_commission_amount) AS site_commission_amount, '.
								'SUM(merchant_orders.merchant_net_amount) AS merchant_net_amount, '.	
								'SUM(merchant_orders.total_amount) AS total_amount '.
								'FROM merchant_orders '.
								
								'WHERE DATE(merchant_orders.created_at) >= \''.$start_date.'\' AND DATE(merchant_orders.created_at) <= \''.$end_date.'\' '.$status_where.
								'GROUP BY DAY(merchant_orders.created_at)'.
								'ORDER BY day_for'
								;
						break;
		}
		$orders = [];
		if(isset($sql)) {
			
			$orders = DB::select($sql);
			
			//DB::getQueryLog();
			//print_r($orders);exit;
		}
;
	 
		
       return view('panels.admin.reports.onlineorder',['start_date' => $start_date,'end_date' => $end_date,'orders' => $orders,'status_where'=> $status_where]);
    }
	
	
	
	
	public function onlineshoping()
    {		
	     
		
       return view('panels.admin.reports.onlineshoping');
       
    }
	
}