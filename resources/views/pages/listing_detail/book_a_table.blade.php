<div class="col-md-12 pad0">
	<div id="table_booking_section" class="select-date Time2">
		<button>Restauarant open from {{$data['open_time']}} to {{$data['close_time']}}</button>
		<h3>Select a Date</h3>

		<div>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<?php
				$days = $data['days'];
				$booking_date = '';
				$booking_time = '';
				$no_of_people = $data['no_of_people'];
				?>
				@foreach($days as $i => $day)
				@php
				if($i == 1)
				{
				$booking_date = $day['day_format2'];
			}
			@endphp
			<li role="presentation" class="@if($i == 1) active @endif">
				<a href="#time_tab_{{ $i }}" aria-controls="home" role="tab" data-toggle="tab" data-booking_date="{{ $day['day_format2'] }}" class="cls_booking_date">
					{{ $day['day_format1'] }}
				</a>
			</li>
			@endforeach

		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			@foreach($days as $i => $day)
			<div role="tabpanel" class="tab-pane @if($i == 1) active @endif" id="time_tab_{{ $i }}">
				<div class="col-md-12 pad0">
					<div class="select-date Time">

						<h3>Time</h3>

						<div>

							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
								<?php
									$times = $data['times'][$i];//print_r($times);exit;
									?>
									@foreach($times as $time_i => $time)
									@php
									$active = '';
									$disabled = '';
									if($i == 0 and $booking_time == '' and $time['disable'] == false)
									{
									$booking_time = $time['format'];
									$active = 'active';
								}
								if($time['disable'])
								{
								$disabled = 'disabled';
							}
							@endphp
							@if($disabled)
							<li role="presentation" class="{{ $active }} disabled">
								<a href="javascript:;" data-booking_time="" class="cls_booking_time">{{ $time['format'] }}</a>
							</li>
							@else
							<li role="presentation" class="{{ $active }}">
								<a href="#" aria-controls="home" role="tab" data-toggle="tab" data-booking_time="{{ $time['format'] }}" class="cls_booking_time">{{ $time['format'] }}</a>
							</li>
							@endif
							@endforeach

						</ul>


					</div>

				</div>
			</div>

		</div>
		@endforeach

	</div>

</div>

<div class="col-md-12 pad0">
	<div class="select-date Time Time1">

		<h3>number of people</h3>
	</div>
</div>
<div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs time-state" role="tablist">

		@for($j=1; $j <= $no_of_people; $j++)
		<li role="presentation" class="@if($j == 1) active @endif">
			<a href="#" aria-controls="home" role="tab" data-toggle="tab" data-no_of_people="{{ $j }}" class="cls_no_of_people">{{ $j }}</a>
		</li>
		@endfor
	</ul>

</div>

<div class="col-md-12 pad0">
	<div class="select-date Time">
		<form name="bookForm" id="bookForm" method="post">
			<div class="col-md-4 pad0">
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="">
				</div>
			</div>
			<div class="col-md-4 ">
				<div class="form-group">
					<label for="phone_number">Phone Number</label>
					<input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="">
				</div>
			</div>
			<div class="col-md-4 pad0">
				<div class="form-group">
					<label for="email">Email </label>
					<input type="email" class="form-control" name="email" id="email" placeholder="">
				</div>
			</div>
			<div class="col-md-12 pad0">
				<div class="form-group">
					<label for="additional_request">Additional Request if any :</label>
					<textarea class="form-control" name="additional_request" id="additional_request" rows="3"></textarea>
				</div>
			</div>
			<div class="col-md-12 pad0">
				<input type="hidden" name="booked_date" id="booking_date" value="{{ $booking_date }}">
				<input type="hidden" name="booked_time" id="booking_time" value="{{ $booking_time }}">
				<input type="hidden" name="total_peoples" id="no_of_people" value="1">
				<input type="hidden" name="listing_id" id="listing_id" value="{{ $listing->id }}">
				<input type="hidden" name="merchant_id" id="merchant_id" value="{{ $listing->user_id }}">
				{{ csrf_field() }}
				<div class="alert alert-success hidden"></div>
				<div class="alert alert-danger hidden"></div>
			</div>
			<div class="col-md-12 pad0">
				<div class="form-group">
					@if(!Auth::check())
						<a href="{{ url('login-redirect') }}" class="btn btn-success" style="padding: 10px; margin: 0px auto; display: block; width: 105px;">Book a Table</a>
					@else
						<button type="button" class="btn btn-success" id="book_table">Book a Table</button>
					@endif
					
				</div>
			</div>
		</form>
	</div>
</div>
</div>

</div>