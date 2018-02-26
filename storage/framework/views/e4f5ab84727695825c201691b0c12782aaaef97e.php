<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.status-panel', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

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


	<!-- MAIN SLIDER -->
	<div class="container-fluid">
	<div class="row">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="1" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3"></li>
	  <li data-target="#myCarousel" data-slide-to="4"></li>
	  <li data-target="#myCarousel" data-slide-to="5"></li>
	  <li data-target="#myCarousel" data-slide-to="6"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
        <img src="<?php echo e(asset('assets/images/ithaanda.jpg')); ?>" id="banner1" class="img-responsive" alt="Los Angeles" style="width:100%;">
      </div>

      <div class="item">
	  <img src="<?php echo e(asset('assets/images/this_is.jpg')); ?>" id="banner2" alt="banner2" class="img-responsive" style="width:100%;">
        
      </div>
    
      <div class="item">
        <img src="<?php echo e(asset('assets/images/scan.jpg')); ?>" id="banner3" alt="banner3" class="img-responsive" style="width:100%;">
      </div>
	  <div class="item">
        <img src="<?php echo e(asset('assets/images/web_available.jpg')); ?>" id="banner4" alt="banner4" class="img-responsive" style="width:100%;">
      </div>
	  <div class="item">
        <img src="<?php echo e(asset('assets/images/vendor_app.jpg')); ?>" id="banner5" alt="banner5" class="img-responsive" style="width:100%;">
      </div>
	  <div class="item">
        <img src="<?php echo e(asset('assets/images/local_community.jpg')); ?>" id="banner6" alt="banner5" class="img-responsive" style="width:100%;">
      </div>
    </div> 

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
</div>
	<!-- END REVOLUTION SLIDER -->
	
	
	<!-- support start -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row mt-xl">
				<div class="col-md-12">

					<div class="tabs tabs-bottom tabs-center tabs-simple mt-sm mb-xl">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tabsNavigationSimpleIcons1" data-toggle="tab">
									<span class="featured-boxes featured-boxes-style-6 p-none m-none">
										<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none">
											<span class="box-content p-none m-none">
												<i class="icon-featured fa fa-fire"></i>
											</span>
										</span>
									</span>									
									<p class="mb-none pb-none">Classifieds</p>
								</a>
							</li>
							<li>
								<a href="<?php echo e(url('local-vendor-listing')); ?>">
									<span class="featured-boxes featured-boxes-style-6 p-none m-none">
										<span class="featured-box featured-box-primary featured-box-effect-6 p-none m-none">
											<span class="box-content p-none m-none">
												<i class="icon-featured fa fa-group"></i>
											</span>
										</span>
									</span>									
									<p class="mb-none pb-none">Local Vendors</p>
								</a>
							</li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane active" id="tabsNavigationSimpleIcons1">
								<div class="row">
									<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-20">
									  <div class="box">
									   <a href="<?php echo e(url('listing/' . $category->slug )); ?>"><span class="width-75"><img alt="" class="width-60 icon-gray" src="<?php echo e(url('/uploads/original') . '/' . $category->c_image); ?>"></span>
									  <span class="icon-text"><?php echo e($category->c_title); ?></span></a>
									  </div>
									  </div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>												  
								</div>
								
							</div>
						</div>
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

	<!-- blog start -->
	<?php if($testimonials): ?>
	<div id="blog" class="blog-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-header">
						<h2 class="title">TESTIMONIALS</h2>
						<img src="<?php echo e(asset('assets/images/blog/01.png')); ?>" alt="Heading">
					</div>
				</div>
				     <div class="col-md-12">
                <div class="carousel slide" data-ride="carousel" id="quote-carousel">
                    <!-- Carousel Slides / Quotes -->
                    <div class="carousel-inner text-center">
                        <!-- Quote 1 -->
						<?php 
						$i = 0;
						foreach($testimonials as $testimonial):
							$description = trim($testimonial->t_description);
							$description = html_excerpt($description, 140, '..');
						?>
                        <div class="item<?php if($i == 0):?> active<?php endif;?>">
                            <blockquote>
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <p><?php echo $description;?></p>
                                        <small><?php echo $testimonial->title;?></small>
                                    </div>
                                </div>
                            </blockquote>
                        </div>
                        <?php 
							$i++;
						endforeach;
						?>
                    </div>
                    <!-- Bottom Carousel Indicators -->
                    <ol class="carousel-indicators">
                        <?php 
						$i = 0;
						foreach($testimonials as $testimonial):
						?>
						<li data-target="#quote-carousel" data-slide-to="<?php echo $i;?>"<?php if($i == 0):?> class="active"<?php endif;?>><img class="img-responsive " src="<?php echo e(url('uploads/original'.'/'.$testimonial->t_image)); ?>" alt="">
                        </li>
						<?php 
							$i++;
						endforeach;
						?>
                    </ol>

                    <!-- Carousel Buttons Next/Prev -->
                    <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
                    <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>
		

			</div>
		</div>
	</div>
	<?php endif; ?>
	<!-- /.end blog -->

	<!-- brand start here -->

	<!-- /.end brand -->

	
	<!-- start newsletter -->
	<div id="newsletter" class="newsletter-section hidden">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="subscribe">
						<div class="section-header">
							<h2 class="title">NEWSLETTER</h2>
							<img src="<?php echo e(asset('assets/images/subscribe/01.png')); ?>" alt="subscribe">
						</div>
						<form action="#" method="post">
							<input type="text" placeholder="Enter your Email" class="form-control">
							<button type="submit">SUBSCRIBE</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div> 
	<!-- end newsletter -->
	</div>	
<?php $__env->stopSection(); ?>
							
<?php $__env->startSection('footer'); ?>
<script type="text/javascript">
/*On Window resize Change Image */
$(window).resize(function(){
	if($(window).width() < 515) {
         $("#banner1").attr("src", "<?php echo e(asset('assets/images/small_banner3.jpg')); ?>");
		 $("#banner2").attr("src", "<?php echo e(asset('assets/images/small_banner4.jpg')); ?>");
		 $("#banner3").attr("src", "<?php echo e(asset('assets/images/small_banner5.jpg')); ?>");
		 $("#banner4").attr("src", "<?php echo e(asset('assets/images/small_banner2.jpg')); ?>");
		 $("#banner5").attr("src", "<?php echo e(asset('assets/images/small_banner.jpg')); ?>");
		 $("#banner6").attr("src", "<?php echo e(asset('assets/images/004.jpg')); ?>");
	}else{
		$("#banner1").attr("src", "<?php echo e(asset('assets/images/ithaanda.jpg')); ?>");
		$("#banner2").attr("src", "<?php echo e(asset('assets/images/this_is.jpg')); ?>");
		$("#banner3").attr("src", "<?php echo e(asset('assets/images/scan.jpg')); ?>");
		$("#banner4").attr("src", "<?php echo e(asset('assets/images/web_available.jpg')); ?>");
		$("#banner5").attr("src", "<?php echo e(asset('assets/images/vendor_app.jpg')); ?>");
		$("#banner6").attr("src", "<?php echo e(asset('assets/images/local_community.jpg')); ?>");
	}
});
/*On Window Load Change Image */
$(window).load(function() {
  if($(window).width() < 515) {
         $("#banner1").attr("src", "<?php echo e(asset('assets/images/small_banner3.jpg')); ?>");
		 $("#banner2").attr("src", "<?php echo e(asset('assets/images/small_banner4.jpg')); ?>");
		 $("#banner3").attr("src", "<?php echo e(asset('assets/images/small_banner5.jpg')); ?>");
		 $("#banner4").attr("src", "<?php echo e(asset('assets/images/small_banner2.jpg')); ?>");
		 $("#banner5").attr("src", "<?php echo e(asset('assets/images/small_banner.jpg')); ?>");
		 $("#banner6").attr("src", "<?php echo e(asset('assets/images/004.jpg')); ?>");
	}else{
		$("#banner1").attr("src", "<?php echo e(asset('assets/images/ithaanda.jpg')); ?>");
		$("#banner2").attr("src", "<?php echo e(asset('assets/images/this_is.jpg')); ?>");
		$("#banner3").attr("src", "<?php echo e(asset('assets/images/scan.jpg')); ?>");
		$("#banner4").attr("src", "<?php echo e(asset('assets/images/web_available.jpg')); ?>");
		$("#banner5").attr("src", "<?php echo e(asset('assets/images/vendor_app.jpg')); ?>");
		$("#banner6").attr("src", "<?php echo e(asset('assets/images/local_community.jpg')); ?>");
	}
});

	$(document).ready(function () {
		
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>