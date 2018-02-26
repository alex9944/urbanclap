<div id="load" style="position: relative;">
@foreach($orderbooking as $orderbooking)	
											
											
											@foreach($orderbooking->merchant_orders as $merchant_orders)
												
											@if($merchant_orders->order_type=='online_shopping')
												<?php
													 $order_detail = $merchant_orders->order_details;
													$listing = $merchant_orders->listing;
													$order_type = $merchant_orders->order_type;
												?>
											<!-- track block start -->
												<div class="track_block">
													<div class="pull-left">
														<p class="order_history_track_number">{{ str_pad($orderbooking->main_order->invoice_id, 10, "0", STR_PAD_LEFT)  }}</p>
													</div>
													<!-- <div class="pull-right">
														<p class="order_history_track">
															<a href="">
																<span><i class="fa fa-map-marker" aria-hidden="true"></i></span> Track
															</a>
														</p>
													</div>-->
												</div>
												<!-- track block end -->
												
												@foreach($order_detail as $order_detail)
												
												<div class="clearfix"></div>
													<div class="col-sm-3 col-md-3">
														@if($merchant_orders->listing->image)
														<img src="{{url('uploads/listing/thumbnail')}}/{{ $merchant_orders->listing->image }}" class="img-responsive">
														@else
														<img src="{{ asset($merchant_layout . '/images/logo-icon-l.png')}}" class="img-responsive">
														@endif												
												
													</div>
												<div class="col-sm-9 col-md-9">
												<!-- left side order title start -->
													<div class="order_his_title pull-left">
														<h3>Samsung Galaxy</h3>

														<p><span class="order_date">Ordered on</span>
														<span><?php echo date('D, M, Y', strtotime($order_detail->order_date))?></span></p>
														<p>Delivered on <?php echo date('D, M, Y', strtotime($order_detail->booked_date))?></p>
														<p class="order_status"><?php if($order_detail->status==0){echo 'Pending';}else{echo 'Confirm';}?></p>
													</div>
												<!-- left side order title end -->
												<!-- right side order title start -->
												<!-- <div class="pull-right">
												<h3><i class="fa fa-inr" aria-hidden="true"></i> 160</h3>
												</div> -->
												<!-- right side order title end -->
												<div class="clearfix"></div>
												<!-- total order start -->
												<div class="text-center order-total-text">
													<p>Price: <i class="fa fa-inr" aria-hidden="true"></i> 24,5400</p>
												</div>
												<!-- total order end -->
												</div>
												
												
												
												
												
												
												@endforeach
												@endif
											@endforeach
											@endforeach
</div>
{{ $orderbooking->links() }}