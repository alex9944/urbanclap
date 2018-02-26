@extends('layouts.main')

@section('content')

    @include('partials.status-panel')
	
	<!-- support start -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row mt-xl">
				<div class="col-md-12">

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-20">
							<a>
								<span class="featured-boxes featured-boxes-style-6 p-none m-none">
									<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none">
										<span class="box-content p-none m-none">
											<i class="icon-featured fa fa-fire"></i>
										</span>
									</span>
								</span>									
								<p class="mb-none pb-none">{{ $category->c_title }}</p>
							</a>
						</div>
					</div>
					<div class="row">
						@foreach($subcategories as $subcategory)
						  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-20">
						  <div class="box">
						  <a href="{{ url('listing/' . $subcategory->category_slug->slug) }}"><span class="width-75"><img alt="" class="width-60 icon-gray" src="{{ url('/uploads/thumbnail') . '/' . $category->c_image }}"></span>
						  <span class="icon-text">{{ $subcategory->c_title }}</span></a>
						  </div>
						  </div>
						@endforeach
																		  
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- end support -->

	
@stop