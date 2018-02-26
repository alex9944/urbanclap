<div class="demo-container" style="height:280px">
	<div id="sales_bar" style="width:100%; height:280px;"></div>
  </div>
  <div class="tiles">
	<div class="col-md-5 tile">
	  <span>Total Orders</span>
	  <h2>{{$total_no_of_orders}}</h2>
	</div>
	<div class="col-md-7 tile">
	  <span>Total Revenue</span>
	  <h2>{{$currency->code.' '.$total_revenue}}</h2>
	  <span class="sparkline_sales graph" style="height: 160px;">
			<canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
	  </span>
	</div>
	
</div>
<script src="{{ asset('admin-assets/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('admin-assets/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('admin-assets/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<script>
$(document).ready(function() {
	<?php if($labels_graph_bar):?>
	if ($('#sales_bar').length){ 
			
		Morris.Bar({
		  element: 'sales_bar',
		  data: [
			<?php echo $labels_graph_bar;?>
		  ],
		  xkey: 'day',
		  ykeys: ['order_amount'],
		  labels: ['Order Amount'],
		  barRatio: 0.4,
		  barColors: ['#005952', '#34495E', '#ACADAC', '#3498DB'],
		  xLabelAngle: 35,
		  hideHover: 'auto',
		  resize: true
		});

	}
	<?php endif;?>
	
	<?php if($price_bar):?>
	$(".sparkline_sales").sparkline([<?php echo $price_bar;?>], {
		type: 'line',
		height: '40',
		width: '200',
		lineColor: '#26B99A',
		fillColor: '#ffffff',
		lineWidth: 3,
		spotColor: '#34495E',
		minSpotColor: '#34495E'
	});
	<?php endif;?>
});
</script>