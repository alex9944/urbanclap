<div class="col-md-12 pad0">
    <div id="appointment_booking_section" class="select-date Time2">
        <button>Clinic open from {{$data['open_time']}} to {{$data['close_time']}}</button>
		<h3>Select a Date</h3>

        <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
            <?php
			$days = $data['days'];
			$booking_date = '';
			$booking_time = '';
			$no_of_people = '1';
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
											if($booking_time == '' and $time['disable'] == false)
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
											<a href="javascript:;">{{ $time['format'] }}</a>
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
			<div class="select-date Time">
				<form name="bookAppointmentForm" id="bookAppointmentForm" method="post">
					<div class="col-md-4 pad0">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Mr.RamaKrishnan">
						</div>
					</div>
					<div class="col-md-4 ">
						<div class="form-group">
							<label for="phone_number">Phone Number</label>
							<input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="(+91) 9841256984">
						</div>
					</div>
					<div class="col-md-4 pad0">
						<div class="form-group">
							<label for="email">Email </label>
							<input type="text" class="form-control" name="email" id="email" placeholder="RamaKrishnan@gmail.com">
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
						<input type="hidden" name="total_peoples" id="no_of_people" value="{{ $no_of_people }}">
						<input type="hidden" name="listing_id" id="listing_id" value="{{ $listing->id }}">
						<input type="hidden" name="merchant_id" id="merchant_id" value="{{ $listing->user_id }}">
						{{ csrf_field() }}
						<div class="alert alert-success hidden"></div>
						<div class="alert alert-danger hidden"></div>
					</div>
					<div class="col-md-12 pad0">
						<div class="form-group">
							<button type="button" class="btn btn-success">Book Appointment</button>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>

</div>
