<style>
.showcomments{
	display:block;
}
</style>
<!-- review content start -->
<h2>Rating & Review</h2>
<div class="col-md-12 pad0">
	<div class="col-md-6 col-md-offset-3">
		<div class="rating_box_layout">
			<div class="rating_count">Ratings:<div class="ratings">{{number_format($avgrating,1)}}</div> / {{sizeof($reviews)}} Reviews</div>
			
			<div class="heat-rating">
				<div class="rating-block one" data-value="1.0"></div>

				<div class="rating-block one-half" data-value="1.5"></div>

				<div class="rating-block two" data-value="2.0"></div>

				<div class="rating-block two-half" data-value="2.5"></div>

				<div class="rating-block three" data-value="3.0"></div>

				<div class="rating-block three-half" data-value="3.5"></div>

				<div class="rating-block four" data-value="4.0"></div>

				<div class="rating-block four-half" data-value="4.5"></div>

				<div class="rating-block five" data-value="5.0"></div>						

				<input type="hidden"  id="ratings-input">
			</div>
		</div>
		
		<div class="col-md-12 pad0">
			<h3>Customer Rating</h3>
			Excellent
			<div class="progress skill-bar ">
				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($excellent,1)}}%;">
					<span class="skill"> <i class="val">{{number_format($excellent,1)}}%</i></span>
				</div>
			</div>
			Good
			<div class="progress skill-bar">
				<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($good,1)}}%;">
					<span class="skill"><i class="val">{{number_format($good,1)}}%</i></span>
				</div>
			</div>
			Average  
			<div class="progress skill-bar">
				<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($average,1)}}%;">
					<span class="skill"><i class="val">{{number_format($average,1)}}%</i></span>
				</div>
			</div>
			Bad 
			<div class="progress skill-bar">
				<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($bad,1)}}%;">
					<span class="skill"><i class="val">{{number_format($bad,1)}}%</i></span>
				</div>
			</div> 


		</div>
		

	</div>

</div>


<div id="allreviews">
	<?php $r_i = 1;?>
	@foreach($reviews as $review)
	@if(isset($review->user) and $review->user)
	<div class="col-md-12 pad0 group @if($r_i <= 5 ) showcomments @endif" style="display:none">
		<span class="head-span"><div id="stars-existing" class="starrr" data-rating='{{$review->rating}}'></div></span>
		<h4>By: {{ucfirst($review->user->first_name)}}. on {{ \Carbon\Carbon::parse($review->created_at->toDateString())->format('M d, Y')}}</h4>
		<p>{{$review->comments}}</p>

	</div>
	<?php $r_i++;?>
	@endif
	@endforeach
</div>
@if($total_reviews>5)
<div class="col-md-12 pad0">
	<div class="ajax-loading" style="display:none;"><img src="{{ asset('assets/images/loader.gif') }}" /></div>
	<div class="form-group" id="seemore_section">
		<button type="button" class="btn btn-success" id="seemore">See More</button>
	</div>
</div>
@endif