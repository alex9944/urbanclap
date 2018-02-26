<div class="orderTabsBox">
	<div class="col-md-4 orderTabs">
		@php
			$new_active = '';
			$pending_active = '';
			$history_active = '';
			if( strpos(Request::url(), 'merchant/orders/pending') !== false ) {
				$pending_active = 'active';
			} else if( strpos(Request::url(), 'merchant/orders/history') !== false ) {
				$history_active = 'active';
			} else {
				$new_active = 'active';
			} 
		@endphp
		<a class="btn btn-cus planbtn {{$new_active}}" href="{{ url('merchant/orders') }}">New Orders</a>
	</div>
	@if($enable_services)
		<?php
		$category_type = $subscribed_category->category_type;
		$enable_pending_order = false;
		if($category_type) {
			$category_types = json_decode($category_type);
			foreach($category_types as $category_type_id)
			{
				if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'table' ) {
					$enable_pending_order = true;
					break;
				} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'appointment' ) {
					$enable_pending_order = true;
					break;
				}
			}
		}
		?>
		@if($enable_pending_order)
		<div class="col-md-4 orderTabs">
			<a class="btn btn-cus historybtn {{$pending_active}}" href="{{ url('merchant/orders/pending') }}">Pending Orders</a>
		</div>
		@endif
	@endif
	<div class="col-md-4 orderTabs">
		<a class="btn btn-cus historybtn {{$history_active}}" href="{{ url('merchant/orders/history') }}">Order History</a>
	</div>
</div>