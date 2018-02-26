@extends('layouts.adminmain')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
        <section id="main-content">
            <section class="wrapper">

                <div class="content-box-large">
                    <h1 style="font-size:28px;">Online Order Reports</h1>

		<div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Order Listing</h2>
                    <div class="filter">
                     
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div id="sales_section" class="col-md-12 col-sm-12 col-xs-12">
                      
                      
					  <!-- Add Form -->
					  
		<form role="form" method="post" id="frmAdd" action="" enctype="multipart/form-data">   
		  <div class="row">
			<div class="col-sm-6">
			  <div class="form-group">
				<label class="control-label" for="input-date-start">Date Start</label>
				  <div class="input-group date startdate_section">
						<input type="text" name="filter_date_start" value="" placeholder="Date Start" data-format="YYYY-MM-DD" id="input-date-start" class="form-control start_date_btn" data-provide="datepicker">
					</div>
			   </div>
			  <div class="form-group">
				<label class="control-label" for="input-date-end">Date End</label>
					<div class="input-group date enddate_section">
						<input type="text" name="filter_date_end" value="" placeholder="Date End" data-format="YYYY-MM-DD" id="input-date-end" class="form-control end_date_btn">
					</div>
			  </div>
		   </div>
		   <div class="col-sm-6">
			 <div class="form-group">
				<label class="control-label" for="input-group">Group By</label>
				      
					<select name="filter_group" id="input-group" class="form-control">
					<option value="day">Days</option> 
					<option value="week">Weeks</option>
					<option value="month">Month</option>
					<option value="year">Year</option>
					
					</select>
			  </div>
			 <div class="form-group">
				<label class="control-label" for="input-status">Order Status</label>
			<select name="filter_merchant_orders_order_status" id="input-status" class="form-control">
						<option value="">All Status</option>				
								<option value="pending">Pending</option>
								<option value="new_order">New Order</option>
								<option value="completed">Completed</option>
								
								<option value="confirmed">Confirmed</option>
								<option value="cancelled">Cancelled</option>
								<option value="progressed">Progressed</option>
			</select>
			 </div>
				<button type="submit" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
			</div>
			</div>
										</div>
			<input type="hidden" name="_token" value="{{csrf_token()}}">
									</form>
					  

                    </div>


                  </div>
                </div>
              </div>
            </div>

                    <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                        <thead> 
						     <tr>
                                <th>Date Start</th>
                                <th>Date End</th>
                                <th>Order Item</th>
                                <th>Commission Amount</th>
								<th>Merchant Amount </th>
								<th>Total Amount</th>
								
                            </tr>
                        </thead>
                        
                        <tbody>
						     @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->start_date }}</td>
                                <td>{{ $order->end_date }}</td>
                                <td>{{ $order->total_items }}</td>
                                <td>{{ $order->site_commission_amount }}</td>
								<td>{{ $order->merchant_net_amount }}</td>
								<td>{{ $order->total_amount }}</td>
								
                            </tr>
							@endforeach 
                        </tbody>
                        
                    </table>

                </div>



        </section>
      </section>
    <div class="clearfix">
</div>
<script>
$(document).ready(function(){
$('.start_date_btn').daterangepicker({
    singleDatePicker: true,
    startDate: moment().subtract(6,'days')
  });
  
  $('.end_date_btn').daterangepicker({
    singleDatePicker: true,
    startDate: moment()
  });

	/*$('.start_date_btn').datepicker({ 
		container: $('.startdate_section'),
		orientation: 'bottom left',
		todayHighlight: true,
		rtl: false,
		format: 'yyyy-mm-dd'
	});
	$('.end_date_btn').datepicker({
		container: $('.enddate_section'),
		orientation: 'bottom left',
		todayHighlight: true,
		rtl: false,
		format: 'yyyy-mm-dd'
	});*/
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
@endsection
