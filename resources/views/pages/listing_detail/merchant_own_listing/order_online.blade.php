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