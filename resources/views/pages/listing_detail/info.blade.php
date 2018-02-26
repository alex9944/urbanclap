<div class="col-md-12 pad0 ">
	<div class="info-mation">
		<div class="col-md-8">
			@if($listing->listing_merchant->mobile_no)
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-phone"></i> Call :</h2>
				<div class="click_to_text call-function slide1">
					Click To Reveal
				</div>
				<div class="call-function slide2 click_to_phone hidden">
					<span class="tc">{{$listing->phoneno}}</span>
				</div>
			</div>
			@endif
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-address-book" aria-hidden="true"></i> Address :</h2>
				<address>
					{!! $data['address'] !!}
				</address>
			</div>
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-info-circle" aria-hidden="true"></i> About :</h2>
				<p>{!! $listing->description !!}</p>
			</div>
		</div>
		<div class="col-md-4">
			@if($listing->working_hours || $listing->holidays)
			<div class="col-md-12 pad0">
				@if($listing->working_hours)
					<h2><i class="fa fa-clock-o" aria-hidden="true"></i> Working Hours :</h2>
					<p>{{$listing->working_hours}}
				@endif
				
				@if(!is_null($listing->holidays) and $listing->holidays)
					<?php 
					$holidays = json_decode($listing->holidays);
					if(!in_array('no', $holidays)):
					?>
					@if(!$listing->working_hours)
						<h2><i class="fa fa-clock-o" aria-hidden="true"></i> Holiday :</h2>
					@else
						<p>Holiday: <br />
					@endif
						<strong>
						@foreach($holidays as $value)
						{{$value}}
						@endforeach
						</strong>
					</p>
					<?php endif;?>
				@endif
			</div>
			@endif
			@if($listing->website)
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-globe"></i> Website :</h2>
				<p><a>{{$listing->website}}</a></p>
			</div>
			@endif
			<?php /*
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-usd" aria-hidden="true"></i> cost :</h2>
				<p class="red">Information Not Available</p>
			</div>
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-credit-card" aria-hidden="true"></i> Payment Method :</h2>
				<p class="red">Information Not Available</p>

			</div>
			*/?>

		</div>
	</div>
</div>