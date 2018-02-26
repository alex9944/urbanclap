  @extends('layouts.'.$user_layout)
  
  @section('header')
<style type="text/css">
	.content-box{
		padding: 32px;
	}
	.img-box{
		height: 368px;
		margin-bottom: 7px;
		border: 1px solid #aaa7a7;
		min-height: 470px;
		padding: 5px;
	}
	.imgtitle span{
		font-size: 14px;
		font-weight: 600;
	}
	.stars-block{
		font-size: 28px;
		font-weight: 600;
		width: 104%;
		margin-left: -3%;
	}
	.starrr {
		font-size: 28px;
		color: #ff7f00;
	}
	.content-box h2{
		margin: 0 !important;
		color: #0f8dc5;
	}
	.content-box h4{
		font-weight: 500;
		color: #716f6f;
	}

	.product-img{
		width: 255px;
		padding: 14px;
		/* margin: 0 auto; */
		margin-left: 21%
	}
	.price-text{
		font-size: 24px;
		font-weight: 700;
		text-align: -webkit-right;	
	}
	.img-box img {
		margin-bottom: 10px;
	}

	.rating_count{
		color: #575656ee;
		font-size: 20px;
		font-weight: 600;
	}
	.progress-bar{
		text-align:center;  
	}
	.excellent{
		background-color: #4CAF50 !important;
	}
	.poor{
		background-color: #e32618 !important;
	}
	.rating_box_layout{
		margin-bottom: 6px
	}  
	.review-box h2{
		text-align: center;
		text-align: center;
		margin: 29px !important;
	}

	ul{
		list-style: none outside none;
		padding-left: 0;
		margin: 0;
	}
	.demo .item{
		margin-bottom: 60px;
	}
	.content-slider li{
		background-color: #ed3020;
		text-align: center;
		color: #FFF;
	}
	.content-slider h3 {
		margin: 0;
		padding: 70px 0;
	}

	.price {
		margin: 0 0 13px 0;
		line-height: 24px;
		color: #e74c3c;
		font-size: 20px;
		font-weight: 700;
	}
	.nopadding {
		padding: 0px;
	}
	.lead{
		margin-bottom: 10px;
	}
	.stock{
		font-weight: 600;
		color: #777575;
		margin-bottom: 1%;
	}
</style>

  @stop
  
  @section('content')
  @include('partials.status-panel')
   <div class="main-content">
  <section class="tab-content-details">
 		<div class="container">
	 		 <div class="row">
	 		 	<div class="col-md-12 content-box">
	 		 		<div class="col-md-6">
	 		 			<div class="demo">
        <div class="item">            
            <div class="clearfix" style="max-width:474px;">
                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
				<li data-thumb="{{url('')}}/upload/images/small/{{ $Products->pro_img}}" > 
						<img src="{{url('')}}/upload/images/medium/{{ $Products->pro_img}}" />
						</li>
											
					@foreach($Products->images as $prod_img)
						<li data-thumb="{{url('')}}/upload/images/small/{{ $prod_img->alt_img}}" > 
						<img src="{{url('')}}/upload/images/medium/{{ $prod_img->alt_img}}" />
						</li>
					@endforeach					
                   
                   
                </ul>
            </div>
        </div>
         

    </div>	
	 		 			  
 
	 		 		</div>
	 		 			<div class="col-md-6"  id="">
	 		 			<div class="col-md-12  " >
	 		 			<h2>{{ucwords($Products->pro_name)}}</h2>
	 		 			<h4>Product Code: {{$Products->pro_code}}</h4>
	 		 			  <div class="lead">
       <div class="stars-block"> <div id="stars" class="col-md-4 col-xs-4">
	   <style>.srate{padding: 2px;font-size: 19px;color: #ff7200;}</style>
	   @for ($i = 0; $i < 5; $i++)
		   @if($i < $avgrating)
		   <i class="fa fa-star srate"></i>
		   @else
			<i class="fa fa-star-o srate"></i>
		   @endif   	
	   @endfor
	   </div>
	   @if(isset($total_reviews) and $total_reviews > 0)
	   <span id="count">{{$avgrating}}</span>/{{isset($total_reviews) ? $total_reviews : 0}}
		@endif
   </div>
       
        
	</div>

	<div class="product_page_price price col-sm-12 nopadding"   itemscope=""  >
<div class="priceblock"><span>Price:</span>
<div class=""></div>
<span class="price-new"><span itemprop="price" id="price-old" >@if($Products->spl_price==0)
															{{$currency->symbol}} {{$Products->pro_price}} /-
														@else
															{{$currency->symbol}} <span style="text-decoration: line-through;">{{$Products->pro_price}}</span> /- <span>{{$Products->spl_price}} /-</span>
														@endif</span></span>
</div>
</div>

<div class="stock"><span> Stock </span> <i class="fa fa-check-square-o"></i> {{$Products->stock}} In Stock</div>

	<div><p>{{ $Products->pro_info }}</p></div>
	 		 			</div>
	 		 		</div>
		<div class="col-md-12"  >			
	@include('pages.listing_detail.product_review')
		</div>
	 		 	</div>

	 		 	
	 		 </div>
	 	</div>
</section>

</div>

@stop
								
@section('footer')	
<script>
  $(document).ready(function(){
    $('#addToCart').hide();
    $('#addToCart_default').show();
    $('#size').change(function(){
     var size = $('#size').val();
     var proDum = $('#proDum').val();


     $.ajax({
      type: 'get',
      dataType: 'html',
      url: '<?php echo url('/selectSize');?>',
      data: "size=" + size + "& proDum=" + proDum,
      success: function (response) {
        console.log(response);
        $('#price').html(response);
        $('#addToCart').hide();
        $('#addToCart_default').show();

        <?php for($i=1;$i<10;$i++){?>
          var colorValue<?php echo $i;?> = $('#colorValue<?php echo $i;?>').val();
          $('#colorClicked<?php echo $i;?>').click(function(){

              // pass color values to color function in Controller
              $.ajax({
                type: 'get',
                dataType: 'html',
                url: '<?php echo url('/selectColor');?>',
                data: "size=" + size + "& proDum=" + proDum + "& color=" + colorValue<?php echo $i;?>,
                success: function (response) {
                  console.log(response);
                  $('#price').html(response);
                  $('#addToCart').show();
                  $('#addToCart_default').hide();


                }
              });

            });
          <?php }?>
        }
      });


   });
    var pre = document.getElementById("preview");
  //  pre.style.visibility = "hidden";
    $('#product').mousemove(function(event){
      var pre = document.getElementById("preview");
      pre.style.visibility = "visible";
      if ($('#product').is(':hover')) {
        var url_img=$('#product').data('img');
        pre.style.backgroundImage = "url('"+url_img+"')";
      }
      var posX = event.offsetX;
      var posY = event.offsetY;
      pre.style.backgroundPosition=(-posX*2.5)+"px "+(-posY*5.5)+"px";

    });
  /*  $('#preview').mousemove(function(event){
      var url_img='';
      var pre = document.getElementById("preview");
      pre.style.visibility = "visible";
      if ($('#product').is(':hover')) {
         url_img=$('#product').data('img');
        var img = document.getElementById("product");
        pre.style.backgroundImage = "url('"+url_img+"')";
      }
      var posX = event.offsetX;
      var posY = event.offsetY;
      pre.style.backgroundPosition=(-posX*2.5)+"px "+(-posY*5.5)+"px";

    });*/
    $('#product').mouseout(function() {
      var pre = document.getElementById("preview");
      pre.style.visibility = "hidden";
    });
    $(".verticalCarousel").verticalCarousel({
      currentItem: 1,
      showItems: 3,

    });
    $('.alt_image').click(function(){
      var image=$(this).data('image');
      $('#product').attr("src","{{url('')}}/upload/images/"+image);
      $('#product').removeAttr('data-img');
      $('#product').attr("data-img","{{url('')}}/upload/images/"+image); 
    });
  });
</script>
<script type="text/javascript" src="{{asset('assets/js/jQuery.verticalCarousel.js')}}"></script>
<script>
	var AuthUser = "{{{ (Auth::user()) ? Auth::user()->id : null }}}";
</script>
<script>
	$(document).ready(function(){
		$('#review-form').validate({
			rules: {
				"reviewer_name": {
					required: true,
				},  
				"reviewer_email": {
					required: true,

				}, 
				"comments":{
					required:true,
				}
			},
			messages: {
				reviewer_name: "Name required",
				reviewer_email: "Enter valid email",
				comments: "Comments required",
			}
		});
		$('.rating-block').click(function(){
			$('#ratings-input').val('');
			var rating=$(this).data('value');
			$('#ratings-input').val(rating);

		});
		$('#review_post').click(function(){
			if($('#review-form').valid()){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				var id='';
				if(AuthUser!=''){
					id=AuthUser;
				}
				else{
					id='';
				}
				var review_data={
					'product_id':'{{$Products->id}}',
					'merchant_id':'{{$Products->merchant_id}}',
					'rating':$('#ratings-input').val(),
					'name':$('#reviewer_name').val(),
					'comments':$('#comments').val(),
					'location':$('#location').text(),
					'email':$('#reviewer_email').val(),
					'user_id':id,
					'_token':CSRF_TOKEN
				};
				$.ajax({
					type:'post',
					url:"{{url('product-review')}}",
					dataType:'json',
					data:review_data,
					async:false,
					success:function(data){ 
					
						
								if(data.status==1)
								{
								$('#review_msg').html('Thank you,Your review posted Successfully..');
								$('#review_msg').show();
								window.setTimeout(function(){location.reload()},3000);
								//location.reload();
								}else{
									$('#review_msg').html('You have already posted the comments');
								$('#review_msg').show();
								}
						
					}
				});
			}
		});
	$('#allreviews div:first-child').toggleClass('showcomments');
	$('.showcomments').css('display','block');
	$('.starrr').removeAttr('style');
		var $group = $('.group');
		$("#seemore").click(function() {
			if ($(this).hasClass('disable')) return false;
			var $hidden = $group.filter(':hidden').toggleClass('showcomments');
			$('.showcomments').css('display','block');
			$('.starrr').removeAttr('style');
		});
	});
</script>
    @stop