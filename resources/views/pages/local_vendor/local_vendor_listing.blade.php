 @extends('layouts.main')
 @section('head')
 <link href="{{ asset($user_layout . '/css/local-vendor.css') }}" rel="stylesheet">
 @stop
  @section('content')
  @include('partials.status-panel')
  <!-- <link href="{{ asset('user-template_v1/css/main.css') }}" rel="stylesheet"> -->
	   <section id="about">		
				<!-- CONTAINER -->
				<div class="container animated fadeInUp" data-appear-top-offset="-200" data-animated="fadeInUp">
				 

               	<h2 class="tit"><i class="fa fa-users" aria-hidden="true"></i>Local Vendors</h2>
          
         
					<div class="ptpb">
	
    
    <div class="filter-det">
        <button class="btn btn-default filter-button" data-filter="all">All</button>
        <button class="btn btn-default filter-button" data-filter="drinks">Drinks</button>
        <button class="btn btn-default filter-button" data-filter="eatables">Eatable</button>
        <button class="btn btn-default filter-button" data-filter="products">Platform Goods</button>
    </div>
    <br/>
    
    <div class="row">
      <?php 
      /*echo '<pre>';

print_r($localvendor); echo '</pre>';
die;*/
      ?>
	@foreach($localvendor as $key=>$vendor)
        <div class="col-md-4  filter {{strtolower($vendor->category)}}">
       
	   
            <div class="each-item br " data-toggle="modal" data-target="#shop-details{{$key}}" >
               <div class="col-md-12 pd0">
			  <?php $users = DB::table('users')
			->select('*')
			->where('users.id', '=', $vendor->user_id)
			->first();
			?>
			<h6 class="shop"><span></span><b>Posted by</b><br><b style="font-weight:normal;font-size: 16px;">{{@$users->first_name}}</b></h6>
			<h3>{{$vendor->title}}</h3>  
               	<img src="{{url('')}}/uploads/localvendor/original/{{ $vendor->image }}"alt="{{ $vendor->title }} class="img-responsive">
               	
				     	<div class="col-md-12 ">
               	<div class="col-md-2">
<i class="fa fa-user" aria-hidden="true"></i>
</div>
<div class="col-md-10">
               	<h4><span>{{$vendor->owner_name}}</span></h4>
          
           </div>
              </div>
            <div class="col-md-12 ">
               	<div class="col-md-2"><i class="fa fa-map-marker" aria-hidden="true" style="color:red;"></i>
			    </div>
             <div class="col-md-10">
               	<address>{{$vendor->address}}</address>
               </div>
             </div>
			 <div class="col-md-12">
               	<div class="col-md-2"><i class="fa fa-user" aria-hidden="true"></i>
                </div>
             <div class="col-md-10">
               	<h4 style="color:#ff8710;text-transform:none !important;">Closing in:<span> {{$vendor->hours}}h:{{$vendor->minutes}}m:{{$vendor->seconds}}s</span></h4>
            </div>
              </div>
           
               </div>
            </div> 
        </div>
		@endforeach
    </div>
</div>
					
				</div><!-- //CONTAINER -->
			
			
		
		</section>
		
	   <!-- Modal -->
	   @foreach($localvendor as $key=>$vendor)
  <div class="modal fade" id="shop-details{{$key}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Modal title</h4>
        </div>
        <div class="modal-body">
		   	<div class="col-md-12">
			
          		<h2 class="tit-shop text-center">{{$vendor->title}}<?php /*<span style="text-transform:none;">500 mtrs. from here</span>*/?></h2>
          	</div>
          <div class="col-md-12 each-item">
          	<div class="col-md-6">
          		     	<div class="col-md-12 ">
			<?php $users = DB::table('users')
			->select('*')
			->where('users.id', '=', $vendor->user_id)
			->first();
			?>
               <h6 class="shop"><span></span><b>Posted By</b><br><b style="color:#318ddc;font-weight:normal;font-size: 16px;">{{@$users->first_name}}</b></h6>
              </div>
             
			  	<div class="col-md-12 pd0">
               	<div class="col-md-2">
<i class="fa fa-user" aria-hidden="true"></i>
</div>
<div class="col-md-10">
               	<h4 style="color:#318ddc;text-transform:uppercase;">{{$vendor->owner_name}}</h4>
          
           </div>
              </div>
          	</div>
          	<div class="col-md-6 pd0">
          		<div class="col-md-12 ">
               	<div class="col-md-2">
<i class="fa fa-map-marker" aria-hidden="true"></i>
</div>
<div class="col-md-10">
               	<address>{{$vendor->address}}</address>
          
           </div>
              </div>
          	</div>
          </div>
          <div class="col-md-12 pd0">
          <!--<div class="col-md-2	"></div>-->
          <div class="vendor-list_img"><img src="{{url('')}}/uploads/localvendor/original/{{ $vendor->image }}"alt="{{ $vendor->title }}" class="img-responsive"></div>
          <!--<div class="col-md-2"></div> -->
          </div>
           <div class="col-sm-12 col-md-12 time-each">
		   <div class="row">
			<div class="col-sm-6 col-md-6"> <h5 style="color:#318ddc;text-transform:uppercase;font-weight:500;">Timings : <span>{{$vendor->working_hours}}</span></h5></div>
          <div class="col-sm-6 col-md-6"><h5 class="mobile-vendor" style="color:#318ddc;text-transform:uppercase;font-weight:500;"><i class="fa fa-phone"></i> :<span>{{$vendor->phone}}</span></h5></div>
          	</div>
        		
				<h5 style="color:#318ddc;text-transform:uppercase;font-weight:500;">About:</h5>
				<p>{{$vendor->description}}</p>
          </div>
        	
        </div>
		
      <!--   <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> -->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
 @endforeach
@stop
								
@section('footer')
    <script>
    $(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
    'use strict';
    /*
	|----------------------------------------------------------------------------
	|   Main Slider
	|----------------------------------------------------------------------------
	*/
	
    </script>
	    <script>
		$(document).ready(function(){
		$(".loadmore").click(function(){
		$(".loading").toggle(500);
		});
		});
		</script>

<!--	<script src="{{ asset('user-template_v1/js/jquery.prettyPhoto.js') }}" ></script>
	<script src="{{ asset('user-template_v1/js/jquery.nicescroll.min.js') }}" ></script>
	<script src="{{ asset('user-template_v1/js/superfish.min.js') }}"></script>
	<script src="{{ asset('user-template_v1/js/jquery.flexslider-min.js') }}" ></script>
	<script src="{{ asset('user-template_v1/js/owl.carousel.js') }}"></script>
	<script src="{{ asset('user-template_v1/js/jquery.mb.YTPlayer.js') }}" ></script>
	<script src="{{ asset('user-template_v1/js/animate.js') }}" ></script>
	<script src="{{ asset('user-template_v1/js/jquery.BlackAndWhite.js') }}"></script>
	<script src="{{ asset('user-template_v1/js/myscript.js') }}"></script> -->
	<script>
		
		//PrettyPhoto
		jQuery(document).ready(function() {
			//$("a[rel^='prettyPhoto']").prettyPhoto();
		});
		
		//BlackAndWhite
/* 		$(window).load(function(){
			$('.client_img').BlackAndWhite({
				hoverEffect : true, // default true
				// set the path to BnWWorker.js for a superfast implementation
				webworkerPath : false,
				// for the images with a fluid width and height 
				responsive:true,
				// to invert the hover effect
				invertHoverEffect: false,
				// this option works only on the modern browsers ( on IE lower than 9 it remains always 1)
				intensity:1,
				speed: { //this property could also be just speed: value for both fadeIn and fadeOut
					fadeIn: 300, // 200ms for fadeIn animations
					fadeOut: 300 // 800ms for fadeOut animations
				},
				onImageReady:function(img) {
					// this callback gets executed anytime an image is converted
				}
			});
		}); */
		
	</script>

	<script type="text/javascript">
	$(document).ready(function(){
// Show or hide the sticky footer button
			
			
			// Animate the scroll to top
			$('.go-top').click(function(event) {
				event.preventDefault();
				
				$('html, body').animate({scrollTop: 0}, 400);
			});
    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
       // alert(value);
        if(value == "all")
        {
            //$('.filter').removeClass('hidden');
            $('.filter').show('1000');
        }
        else
        {
//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
    });

});
</script>
  <script src="{{ asset('user-template_v1/js/slick.min.js') }}"></script>    
    <script src="{{ asset('user-template_v1/js/wow.min.js') }}"></script>  

  

<!-- Mirrored from uigigs.com/themeforest/html/oneplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 28 Apr 2017 12:36:13 GMT -->
@stop