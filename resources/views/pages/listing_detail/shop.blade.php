<style>
.slider.slider-horizontal{margin:10px;}

</style>
<div role="tabpanel" class="tab-pane active" id="order">
					     	<div class="col-md-12">
					     		<div class="col-md-3 pad0">
					     		<div class="departments">
					     			<div class="col-md-12">
					     				<h2>Departments</h2>
					     				<ul>
					     					  <?php $cats = DB::table('shop_pro_cat')->join('shop_products','shop_products.cat_id','=','shop_pro_cat.id')->orderby('name', 'ASC')->groupBy('shop_pro_cat.id')->get();?>
            @foreach($cats as $cat)<li><input type="checkbox" id="brandId" value="{{$cat->cat_id}}" class="try"/>{{ucwords($cat->name)}}</li>
			 @endforeach
					     								     				

					     				</ul>
					     		
					     			</div>
					     				<div class="col-md-12">
					     				<h2>Budget</h2>
										 <div id="slider-range"></div>
										  <b class="pull-left">{{$currency->symbol}}
            <input size="5" type="text" id="amount_start" name="min_price"
            value="{{$minmax->MIN_PRICE}}" style="border:0px; font-weight: bold; color:green" readonly="readonly" /></b>

          <b class="pull-right">{{$currency->symbol}}
            <input size="5" type="text" id="amount_end" name="max_price" value="{{$minmax->MAX_PRICE}}"
              style="border:0px; font-weight: bold; color:green" readonly="readonly"/></b>
					     				    <?php /* <div data-role="rangeslider">
     
        <input type="range" name="price-min" id="price-min" value="200" min="0" max="1000">
    
        <input type="range" name="price-max" id="price-max" value="800" min="0" max="1000">
      </div>*/?>
					     				
					     			</div>
									
									
									
					     			<?php /*<div class="col-md-12">
					     				<h2>Customer Rating</h2>

					     				 <div class="progress skill-bar ">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    <span class="skill"> <i class="val">100%</i></span>
                </div>
            </div>
                
            <div class="progress skill-bar">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" >
                    <span class="skill"><i class="val">90%</i></span>
                </div>
            </div>
            
            <div class="progress skill-bar">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                    <span class="skill"><i class="val">75%</i></span>
                </div>
            </div>  
            
            <div class="progress skill-bar">
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                    <span class="skill"><i class="val">55%</i></span>
                </div>
            </div>  
					     				
					     				
					     			</div>
					     			<div class="col-md-12">
					     				<h2>offers</h2>
					     				<ul>
					     					
					     					<li><a href="">Special Price</a></li>
					     					<li><a href="">Discount price</a></li>
					     					<li><a href="">Buy one Get one</a></li>
					     					

					     				</ul>
					     				<p><a href="#">See more</a></p>
					     			</div>
					     			<div class="col-md-12">
					     				<h2>Condition</h2>
					     				<ul>
					     					<li><a href="">New</a></li>
					     					<li><a href="">Old</a></li>


					     				</ul>
					     				
					     			</div>*/?>
					     		</div><!-- departments end -->
					     			









					     		</div>
					     		<div class="col-md-9 pad0">
					     		<div class="col-md-12" id="updateDiv">
									<?php
									if (isset($msg)) {
										echo $msg;
									} else {?> 
										<h2> Shop Online   </h2>
									<?php } ?>
									<?php if (empty($Products)) { ?>
											Sorry, products not found
									<?php } else {$countP=0;?>
									@foreach($Products as $product)
											<div class="col-md-4 ">
												<div class="product-description">
												 <a href="{{url('/product_details')}}/{{$product->id}}"><img src="{{url('')}}/upload/images/small/{{ $product->pro_img}}"></a>
												 <h2 class="text-center"><a href="{{url('/product_details')}}/{{$product->id}}">{{$product->pro_name}}</a></h2>
												 <h4 class="text-center">by {{$product->categories->name}}</h4>
													<h1 class="text-center">
														@if($product->spl_price==0)
															{{$currency->symbol}} {{$product->pro_price}} /-
														@else
															{{$currency->symbol}} <span style="text-decoration: line-through;">{{$product->pro_price}}</span> /- <span>{{$product->spl_price}} /-</span>
														@endif
													</h1> 
											<div class="lead text-center">

											<button class="btn btn-success add-to-cart" data-id="{{$product->id}}" id="cartBtn<?php echo $countP;?>">Add to Cart</button>
											<div id="successMsg<?php echo $countP;?>" class="alert alert-success"></div>

											</div>													
											<?php /*	 <div class=" lead">
			   
													<div id="stars-existing" class="starrr" data-rating='4'></div>
												   
												</div>
												 <div class="rating_count">Ratings:<div class="ratings">2.0</div> / 192 Reviews</div>*/?>
												</div>
												</div>
									<?php $countP++?>
									@endforeach
									<?php } ?>
									
									
					     			
					     			</div>
					     			
					     		
					     			
					     		
					     		</div>
					     	</div>




					     </div>