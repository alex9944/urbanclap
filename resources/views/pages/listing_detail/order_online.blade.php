<div class="col-md-12 sangetha-rest">

	<div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
		<?php
		$iter = 1;
		?>
		@foreach($orderonline_menus as $menu_id => $menu_name)
			<li role="presentation" @if($iter == 1) class="active" @endif><a href="#menu_{{ $menu_id }}" aria-controls="home" role="tab" data-toggle="tab">{{ $menu_name }}</a></li>
			<?php
			$iter++;
			?>
		@endforeach
		</ul>

  <!-- Tab panes -->
	<div class="tab-content">
		<?php
		$iter = 1;
		?>
		@foreach($orderonline_menus as $menu_id => $menu_name)
		<div role="tabpanel" class="tab-pane  @if($iter == 1) active @endif" id="menu_{{ $menu_id }}">
			<div class="col-md-12">
				@if(isset($orderonline_menu_items[$menu_id]['veg']))
				<div class="col-md-6">
					<div class="table-responsive">
						<table class="table">
							@foreach($orderonline_menu_items[$menu_id]['veg'] as $menu_item)
							<tr>
								<td>
									<div class="green-border">
										<div class="green-width"></div>
									</div>
								</td>
								<td>{{ $menu_item->item_name }}</td>
								@php
									$price = $menu_item->original_price;
									$del = '';
									if($menu_item->discounted_price)
									{
										$price = $menu_item->discounted_price;
										$del = '<del>' . $menu_item->original_price . '</del> ';
									}
								@endphp
								<td><?php echo $del;?>{{ $currency->symbol . $price }}</td>
								<td>
									
									<?php
										$cart_item = \Cart::get('food_'.$menu_item->item_id);
										$cart_button_data =  'data-item_id="'.$menu_item->item_id.'" data-name="'.$menu_item->item_name.'" data-price="'.$price.'" data-listing_id="'.$listing->id.'" data-stock="'.$menu_item->stock.'" data-order_type="food"';
									?>
									
									@if( !empty($cart_item) )
										<div class="plus-minus">
											<div class="center">

												<div class="input-group">
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-danger btn-number"  data-type="minus"<?php echo $cart_button_data;?>>
						<span class="glyphicon glyphicon-minus"></span>
													</button>
													</span>
													<input type="text" name="quantity[]" id="quantity_{{ $menu_item->item_id }}" class="cls_quantity form-control input-number" value="{{ $cart_item->quantity }}" min="1" max="100"<?php echo $cart_button_data;?>>
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-success btn-number" data-type="plus"<?php echo $cart_button_data;?>>
						  <span class="glyphicon glyphicon-plus"></span>
													</button>
													</span>
												</div>

											</div>

										</div>
									@else
										<button id="add_button_{{ $menu_item->item_id }}" type="button" class="add_item_via_ajax_form btn btn-success btn-width" data-type="" <?php echo $cart_button_data;?>>Add</button>
									
										<div id="plus_minus_{{ $menu_item->item_id }}" class="hidden plus-minus">
											<div class="center">

												<div class="input-group">
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-danger btn-number"  data-type="minus"<?php echo $cart_button_data;?>>
						<span class="glyphicon glyphicon-minus"></span>
													</button>
													</span>
													<input type="text" name="quantity[]" id="quantity_{{ $menu_item->item_id }}" class="cls_quantity form-control input-number" value="1" min="1" max="100"<?php echo $cart_button_data;?>>
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-success btn-number" data-type="plus"<?php echo $cart_button_data;?>>
						  <span class="glyphicon glyphicon-plus"></span>
													</button>
													</span>
												</div>

											</div>

										</div>
									@endif
									
								</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				@endif
				@if(isset($orderonline_menu_items[$menu_id]['non-veg']))
				<div class="col-md-6">
					<div class="table-responsive">
						<table class="table">
							@foreach($orderonline_menu_items[$menu_id]['non-veg'] as $menu_item)
							<tr>
								<td>
									<div class="red-border">
										<div class="red-width"></div>
									</div>
								</td>
								<td>{{ $menu_item->item_name }}</td>
								@php
									$price = $menu_item->original_price;
									$del = '';
									if($menu_item->discounted_price)
									{
										$price = $menu_item->discounted_price;
										$del = '<del>' . $menu_item->original_price . '</del> ';
									}
								@endphp
								<td><?php echo $del;?>{{ $currency->symbol . $price }}</td>
								<td>
									
									<?php
										$cart_item = \Cart::get('food_'.$menu_item->item_id);
										$cart_button_data =  'data-item_id="'.$menu_item->item_id.'" data-name="'.$menu_item->item_name.'" data-price="'.$price.'" data-listing_id="'.$listing->id.'" data-stock="'.$menu_item->stock.'" data-order_type="food"';
									?>
									
									@if( !empty($cart_item) )
										<div class="plus-minus">
											<div class="center">

												<div class="input-group">
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-danger red btn-number"  data-type="minus"<?php echo $cart_button_data;?>>
						<span class="glyphicon glyphicon-minus"></span>
													</button>
													</span>
													<input type="text" name="quantity[]" id="quantity_{{ $menu_item->item_id }}" class="cls_quantity form-control input-number" value="{{ $cart_item->quantity }}" min="1" max="100"<?php echo $cart_button_data;?>>
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-success red btn-number" data-type="plus"<?php echo $cart_button_data;?>>
						  <span class="glyphicon glyphicon-plus"></span>
													</button>
													</span>
												</div>

											</div>

										</div>
									@else
										<button id="add_button_{{ $menu_item->item_id }}" type="button" class="add_item_via_ajax_form btn btn-success red btn-width" data-type="" <?php echo $cart_button_data;?>>Add</button>
									
										<div id="plus_minus_{{ $menu_item->item_id }}" class="hidden plus-minus">
											<div class="center">

												<div class="input-group">
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-danger red btn-number"  data-type="minus"<?php echo $cart_button_data;?>>
						<span class="glyphicon glyphicon-minus"></span>
													</button>
													</span>
													<input type="text" name="quantity[]" id="quantity_{{ $menu_item->item_id }}" class="cls_quantity form-control input-number" value="1" min="1" max="100"<?php echo $cart_button_data;?>>
													<span class="input-group-btn">
					  <button type="button" class="add_item_via_ajax_form btn btn-success red btn-number" data-type="plus"<?php echo $cart_button_data;?>>
						  <span class="glyphicon glyphicon-plus"></span>
													</button>
													</span>
												</div>

											</div>

										</div>
									@endif
									
								</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				@endif
			</div>
			<?php
			$iter++;
			?>
		</div>
		@endforeach
	</div>

</div>

</div>