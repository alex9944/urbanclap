@extends('layouts.main')

@section('content')

@include('partials.status-panel')

<?php
function html_excerpt( $str, $count=500, $end_char = '&#8230;' ) {
	$full_str = $str = strip_all_tags( $str, true );
	$str = mb_substr( $str, 0, $count );
	// remove part of an entity at the end
	$str = preg_replace( '/&[^;\s]{0,6}$/', '', $str );
	
	$str = wordwrap($str, 30, "\n", TRUE);
	
	if(strlen($full_str) <= $count)
	{		
		return $str;
	}
	else
	{
		return trim($str).$end_char;
	}
}
function strip_all_tags($string, $remove_breaks = false) {
	$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
	$string = strip_tags($string);
	if ( $remove_breaks ) {
		$string = preg_replace('/[\r\n\t ]+/', ' ', $string);
	}
	return trim( $string );
}
?>


	
	
	
	<!-- support start -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row mt-xl">
				<div class="col-md-12">

					<div class="tabs tabs-bottom tabs-center tabs-simple mt-sm mb-xl">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tabsNavigationSimpleIcons1" data-toggle="tab">
																	
									<p class="mb-none pb-none">Welcome To urbanclap</p>
								</a>
							</li>
						
						</ul>
						
					
					</div>

					<?php /*
					<p class="center">
						<a class="btn btn-default mt-md loadmore">Load More <i class="fa fa-angle-right pl-xs"></i></a>
					</p>
					*/?>
				</div>
			</div>

		</div>
		
	</div>
	<!-- end support -->


	</div>	
@stop
							
@section('footer')
<script type="text/javascript">
/*On Window resize Change Image */
$(window).resize(function(){
	if($(window).width() < 515) {
         $("#banner1").attr("src", "{{ asset('assets/images/small_banner3.jpg') }}");
		 $("#banner2").attr("src", "{{ asset('assets/images/small_banner4.jpg') }}");
		 $("#banner3").attr("src", "{{ asset('assets/images/small_banner5.jpg') }}");
		 $("#banner4").attr("src", "{{ asset('assets/images/small_banner2.jpg') }}");
		 $("#banner5").attr("src", "{{ asset('assets/images/small_banner.jpg') }}");
		 $("#banner6").attr("src", "{{ asset('assets/images/004.jpg') }}");
	}else{
		$("#banner1").attr("src", "{{ asset('assets/images/ithaanda.jpg') }}");
		$("#banner2").attr("src", "{{ asset('assets/images/this_is.jpg') }}");
		$("#banner3").attr("src", "{{ asset('assets/images/scan.jpg') }}");
		$("#banner4").attr("src", "{{ asset('assets/images/web_available.jpg') }}");
		$("#banner5").attr("src", "{{ asset('assets/images/vendor_app.jpg') }}");
		$("#banner6").attr("src", "{{ asset('assets/images/local_community.jpg') }}");
	}
});
/*On Window Load Change Image */
$(window).load(function() {
  if($(window).width() < 515) {
         $("#banner1").attr("src", "{{ asset('assets/images/small_banner3.jpg') }}");
		 $("#banner2").attr("src", "{{ asset('assets/images/small_banner4.jpg') }}");
		 $("#banner3").attr("src", "{{ asset('assets/images/small_banner5.jpg') }}");
		 $("#banner4").attr("src", "{{ asset('assets/images/small_banner2.jpg') }}");
		 $("#banner5").attr("src", "{{ asset('assets/images/small_banner.jpg') }}");
		 $("#banner6").attr("src", "{{ asset('assets/images/004.jpg') }}");
	}else{
		$("#banner1").attr("src", "{{ asset('assets/images/ithaanda.jpg') }}");
		$("#banner2").attr("src", "{{ asset('assets/images/this_is.jpg') }}");
		$("#banner3").attr("src", "{{ asset('assets/images/scan.jpg') }}");
		$("#banner4").attr("src", "{{ asset('assets/images/web_available.jpg') }}");
		$("#banner5").attr("src", "{{ asset('assets/images/vendor_app.jpg') }}");
		$("#banner6").attr("src", "{{ asset('assets/images/local_community.jpg') }}");
	}
});

	$(document).ready(function () {
		
	});
</script>
@stop