
<script>
  $(document).ready(function(){
    <?php $maxP = count($Products);
    for($i=0;$i<$maxP; $i++) {?>
      $('#successMsg<?php echo $i;?>').hide();
      $('#cartBtn<?php echo $i;?>').click(function(){
        var pro_id<?php echo $i;?> = $('#pro_id<?php echo $i;?>').val();

        $.ajax({
          type: 'get',
          url: '<?php echo url('/cart/addItem');?>/'+ pro_id<?php echo $i;?>,
		  dataType: "json",
          success:function(response){
			if(response.status) {				
				var data = response.data;
				ajax_update_mini_cart(data);
			} else {
				alert(response.msg);
			}
          }
        });

      });
      <?php }?>
    });
  </script>
  <style>
  .ui-widget-header {
    background: #f57629 !important;
  }
  .ui-state-active{
    background: #0089cf !important;
  }
   .productinfo {
    position: relative;
}
.product-overlay {
    background: #FE980F;
    top: 0;
    display: none;
    height: 0;
    position: absolute;
    transition: height 500ms ease 0s;
    width: 100%;
    display: block;
    opacity: ;
}
.product-overlay .overlay-content {
    bottom: 0;
    position: absolute;
    bottom: 0;
    text-align: center;
    width: 100%;
}
.product-image-wrapper {
    border: 1px solid #F7F7F5;
    overflow: hidden;
    /* margin-bottom: 30px; */
}
.single-products {
    position: relative;
}
.brandLi b {
    font-size: 16px;
    color: #FE980F;
}
</style>

<div class="row" id="all_products">
  <div class="col-sm-3">
    <div class="left-sidebar">

      <div class="price-range"><!--price-range-->
      
		<?php $minmax = DB::select("SELECT  MIN(pro_price) AS MIN_PRICE , MAX(pro_price) AS MAX_PRICE FROM shop_products"); 
		
	
		//print_r($minmax);
		?>
		 @foreach($minmax as $price)
	
		 
		<div class="well">
          <h2>Price Range</h2>
          <div id="slider-range"></div>
          <br>
		 <b class="pull-left">{{$currency->symbol}}
            <input size="2" type="text" id="amount_start" name="min_price"
            value="{{$price->MIN_PRICE}}" style="border:0px; font-weight: bold; color:green" readonly="readonly" /></b>

          <b class="pull-right">{{$currency->symbol}}
            <input size="5" type="text" id="amount_end" name="max_price" value="{{$price->MAX_PRICE}}"
              style="border:0px; font-weight: bold; color:green" readonly="readonly"/></b>
        </div>
		@endforeach

      </div><!--/price-range-->
           <!--  <div style="float:left; width:26%">

              <p>
                <label for="amount">Price range:</label>
                <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
              </p>

              <div id="slider-range"></div>



            </div> -->

      <div class="brands_products"><!--brands_products-->
        <div class="brands-name">
          <h2>Brands</h2>
          <ul class="nav nav-pills nav-stacked">

            <?php $cats = DB::table('shop_pro_cat')->join('shop_products','shop_products.cat_id','=','shop_pro_cat.id')->orderby('name', 'ASC')->groupBy('shop_pro_cat.id')->get();?>
            @foreach($cats as $cat)
              <li class="brandLi"><input type="checkbox" id="brandId" value="{{$cat->cat_id}}" class="try"/>
                     <!-- <span class="pull-right">({{App\Models\shopproducts::where('cat_id',$cat->id)->count()}})</span> -->
                <b>  {{ucwords($cat->name)}}</b></li>
            @endforeach
          </ul>
        </div>
      </div><!--/brands_products-->

    </div>
  </div>

  <div class="col-sm-9 padding-right"  id="updateDiv" >

    <div class="features_items"> <!--features_items-->
            <!-- <b> Total Products</b>:  {{$Products->count()}} -->
      <h2 class="title text-center">
        <?php
          if (isset($msg)) {
            echo $msg;
          } else {
        ?> Products <?php } ?> </h2>
        <?php if (empty($Products)) { ?>
          sorry, products not found
        <?php } else {
          $countP=0;?>
          @foreach($Products as $product)
            <input type="hidden" id="pro_id<?php echo $countP;?>" value="{{$product->prod_id}}"/>
            <div class="col-sm-4" >
              <div class="product-image-wrapper">
                <div class="single-products">
                  <div class="productinfo text-center">
                    <a href="{{url('/product_details')}}/{{$product->prod_id}}">
                      <img src="{{url('')}}/upload/images/small/<?php echo $product->pro_img; ?>" alt="" class="img-responsive" style="margin-left: 75px;"/>
                    </a>

                    <h2 id="price">
                      @if($product->spl_price==0)
                        {{$currency->symbol}}{{$product->pro_price}}
                       @else
                        <img src="{{URL::asset('img/alt_images/sale.png')}}" style="width:60px"/>
                        <span style="text-decoration:line-through; color:#ddd">
                        {{$currency->symbol}}{{$product->pro_price}} </span>
                        {{$currency->symbol}}{{$product->spl_price}}
                      @endif

                    </h2>

                    <p><a href="{{url('/product_details')}}/{{$product->prod_id}}" style="color: #428bca;    font-weight: 900;"><?php echo $product->pro_name; ?></a></p>
                  </div>

                </div>
              </div>
            </div>
            <?php $countP++?>
          @endforeach
        <?php } ?>
      </div>

    </div><!--features_items-->

  </div>

  <script type="text/javascript">
    $(document).ready(function(){
      $("#slider-range").slider({
        range: true,
        min: {{$price->MIN_PRICE}},
        max: {{$price->MAX_PRICE}},
        step: 500,
       // values: [300, 1000],
        slide: function (event, ui) {

          $("#amount_start").val(ui.values[ 0 ]);
          $("#amount_end").val(ui.values[ 1 ]);
          var start = $('#amount_start').val();
          var end = $('#amount_end').val();
          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         var listing_id='<?php echo $listing->id;?>';
          var url="{{url('')}}/productbyprice";
          var data={'_token':CSRF_TOKEN,'start':start,'end':end,'listing':listing_id};
          $('#updateDiv').html('');
          filterproduct(url,data);
          }
        });

        $('.try').click(function(){
          var brand = [];
          $('.try').each(function(){
            if($(this).is(":checked")){

              brand.push($(this).val());
            }
          });
          Finalbrand  = brand.toString();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url="{{url('')}}/productbybrand";
             var listing_id='<?php echo $listing->id;?>';
            var data={'brand':Finalbrand,'_token':CSRF_TOKEN,'listing':listing_id};
             $('#updateDiv').html('');
             filterproduct(url,data);
        });
        function filterproduct(url,data){
           var filterbyprice='';
          $.ajax({
            type: 'post',
            dataType: "json",
            url: url,
            data: data,
            success: function (response) {
              filterbyprice+='<div class="features_items">\
                                <h2 class="title text-center">\
                                Features Item </h2>';
                                if(response.length==0){
                                  filterbyprice+=' sorry, products not found';
                                }
                                else{
                                  $.each(response,function(key,value){
                                    filterbyprice+='<div class="col-sm-4" >\
                                      <div class="product-image-wrapper">\
                                        <div class="single-products">\
                                          <div class="productinfo text-center">\
                                            <a href="{{url('/product_details')}}/'+value.id+'">\
                                              <img src="{{url('')}}/upload/images/small/'+value.pro_img+'" alt="" />\
                                            </a>\
                                            <h2 id="price">{{$currency->symbol}}'+value.pro_price+'</h2>\
                                            <p><a href="{{url('/product_details')}}/'+value.id+'">'+value.pro_name+'</a></p>\
                                            <a href="{{url('/cart/addItem')}}/'+value.id+'" class="btn btn-success add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>\
                                          </div>\
                                        </div>\
                                        <div class="choose">';
                                        <?php if(Auth::check()){?>
                                       if (value.wishlist==0){
                                          filterbyprice+='<form action="{{url('/addToWishList')}}" method="post">\
                                            {{ csrf_field() }}\
                                            <input type="hidden" value="'+value.id+'" name="pro_id"/>\
                                            <p align="center">\
                                                <input type="submit" value="Add to WishList" class="btn btn-primary"/>\
                                            </p>\
                                          </form>';
                                         } else { 
                                          filterbyprice+='<h5 style="color:green"> Added to <a href="{{url('/WishList')}}">wishList</a></h5>';
                                       } 
                                       <?php }?>
                                      filterbyprice+='</div>\
                                    </div>\
                                  </div>';
                                });
                                filterbyprice+='</div>';
                                }  
                                $('#updateDiv').html(filterbyprice);
              }
            });
        }
      });
    </script>

