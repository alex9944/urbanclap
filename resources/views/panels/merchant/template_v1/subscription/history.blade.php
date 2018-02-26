<div class="tab-pane row historytogg" id="historytogg">
	<div class="col-md-12 mt10 col-md-offset-1">
		@foreach ($subscribers as $subscriber)
			<?php
				$plan_detail = json_decode($subscriber->plan_detail);
				$plan = $plan_detail->subscription_pricing->plan.', '.$subscriber->currency->symbol.$plan_detail->subscription_pricing->price.' - '.$plan_detail->subscription_term->display_value;
				$category = $plan_detail->subscription_category->title;
			?>
			<div class="col-md-12 pad10">
				<div class="col-md-6">
					<div class=" ">
						<div class="form-group ">
							<label for="name" class="col-lg-4 col-xs-4">Plan :</label>
							<div class="col-lg-8">
								<span>{{$plan}}</span>
							</div>
						</div>
					</div>
					<div class=" ">
						<div class="form-group ">
							<label for="name" class="col-lg-4 col-xs-4">Date:</label>
							<div class="col-lg-8">
								<span><?php echo date('F d, Y', strtotime($subscriber->created_at));?></span>
							</div>
						</div>
					</div>
					<div class=" ">
						<div class="form-group ">
							<label for="name" class="col-lg-4 col-xs-4">Status:</label>
							<div class="col-lg-8">
								<span>{{$subscriber->payment_status}}</span>
							</div>
						</div>
					</div>
					<div class=" ">
						<div class="form-group ">
							<label for="name" class="col-lg-4 col-xs-12">Action :</label>
							<div class="col-lg-8 col-xs-12">
								<span class="col-md-5 col-sm-3"><button class="view_order btn btn-primary btn-sm" id="{{ $subscriber->id }}">View detail</button></span>
							</div>
						</div>
					</div>

				</div>

			</div>
			<hr>
		@endforeach

	</div>
</div>