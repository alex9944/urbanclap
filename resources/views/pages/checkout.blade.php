 @extends('layouts.main')
 @section('content')
 @include('partials.status-panel')
<style>
.step-one {
    margin-bottom: -10px;
}
.step-one .heading {
    background: none repeat scroll 0 0 #F0F0E9;
    color: #363432;
    font-size: 20px;
    margin-bottom: 35px;
    padding: 10px 25px;
    font-family: 'Roboto', sans-serif;
}
.order-message textarea {
    font-size: 12px;
    height: 335px;
    margin-bottom: 20px;
    padding: 15px 20px;
    background: #F0F0E9;
    border: 0;
    color: #696763;
    width: 100%;
    border-radius: 0;
    resize: none;
}
.cart_quantity_input {
    color: #696763;
    float: left;
    font-size: 16px;
    text-align: center;
    font-family: 'Roboto',sans-serif;
}
#cart_items .cart_info .cart_menu {
    background: #FE980F;
    color: #fff;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
}
.cart_description{
    padding: 15px !important;
}
.payment-options {
    margin-bottom: 125px;
    margin-top: -25px;
}
</style>
 <section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Check out</li>
            </ol>
        </div><!--/breadcrums-->

        <div class="step-one">
            <h2 class="heading">Step1</h2>
        </div>




        <?php // form start here?>
        <form action="{{url('/')}}/formvalidate/items" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="shopper-informations">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="shopper-info">
                            <p>Shopper Information</p>

                            <input type="text" name="fullname"  placeholder="Display Name" class="form-control"  value="{{ old('fullname') }}">

                            <span style="color:red">{{ $errors->first('fullname') }}</span>
                            <hr>
                            <input type="text" name="address"  placeholder="Address" class="form-control"  value="{{ old('address') }}">

                            <span style="color:red">{{ $errors->first('address') }}</span>
                            <hr>
                            <input type="text" placeholder="State Name" name="state" class="form-control" value="{{ old('state') }}">

                            <span style="color:red">{{ $errors->first('state') }}</span>

                            <hr>
                            <input type="text" placeholder="Pincode" name="pincode" class="form-control" value="{{ old('pincode') }}">

                            <span style="color:red">{{ $errors->first('pincode') }}</span>

                            <hr>
                            <input type="text" placeholder="City Name" name="city" class="form-control" value="{{ old('city') }}">

                            <span style="color:red">{{ $errors->first('city') }}</span>

                            <hr>

                            <select name="country" class="form-control" >
                                <option value="{{ old('country') }}" selected="selected">Select country</option>
                                <option value="United States">United States</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="UK">UK</option>
                                <option value="India">India</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Ucrane">Ucrane</option>
                                <option value="Canada">Canada</option>
                                <option value="Dubai">Dubai</option>
                            </select>
                            <span style="color:red">{{ $errors->first('country') }}</span>




                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="order-message">
                            <p>Shipping Order</p>
                            <textarea name="message"  placeholder="Notes about your order, Special Notes for Delivery" rows="16"></textarea>
                            <label><input type="checkbox"> Shipping to bill address</label>
                        </div>
                    </div>
                </div>
            </div>

            <?php // form end here?>

            <div class="review-payment">
                <h2>Review & Payment</h2>
            </div>

            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                        <tr class="cart_menu">
                            <td class="image">Item</td>
                            <td class="description"></td>
                            <td class="price">Price</td>
                            <td class="quantity">Quantity</td>
                            <td class="total">Total</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $cartItem)
                        <tr>
                            <td class="cart_product">
                                <a href=""><img src="images/cart/one.png" alt=""></a>
                            </td>
                            <td class="cart_description">
                             <div class="col-md-3">
                                <h4 class="title"><?php echo $cartItem->name;?>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <?php if(isset($cartItem->attributes['img'])){?>
                                <img src="{{url('')}}/upload/images/small/{{$cartItem->attributes->img}}" style="margin-top:-15px;"/><?php }?>
                            </div>
                            <p>Web ID: {{$cartItem->id}}</p>
                        </td>
                        <td class="cart_price">
                            <p>{{$currency->symbol}}{{$cartItem->price}}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                <input class="cart_quantity_input" type="text" value="<?php echo $cartItem->quantity;?>" readonly="readonly" size="2">

                            </div>
                        </td>
                        <td>
                            <div class="rate"><?php echo $currency->symbol . ' ' . $cartItem->getPriceSum();?></div>
                        </td>
                        <td>
                            <a class="cart_quantity_delete" 
                            href="{{url('/cart/remove')}}/{{$cartItem->id}}"><i class="fa fa-trash-o"></i></button></a>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">
                            <table class="table table-condensed total-result">
                                <tr>
                                    <td>Cart Sub Total</td>
                                    <td><?php echo $currency->symbol . ' '. \Cart::getSubTotal();?></td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td><span><?php echo $currency->symbol . ' '. \Cart::getTotal();?></span></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="payment-options">
            <span>
                <input type="radio" name="pay" value="COD" checked="checked" id="cash"> COD

            </span>
            <span>
                <input type="radio" name="pay" value="paypal" id="paypal"> PayPal
                  @include('pages.paypal')
            </span>

            <span>
                <input type="submit" value="COD" class="btn btn-primary" id="cashbtn">
            </span>
        </div>
    </div>

</form>





<script>

    $('#paypalbtn').hide();
          //  $('#cashbtn').hide();

          $(':radio[id=paypal]').change(function(){
            $('#paypalbtn').show();
            $('#cashbtn').hide();

        });

          $(':radio[id=cash]').change(function(){
            $('#paypalbtn').hide();
            $('#cashbtn').show();

        });
    </script>
</section> <!--/#cart_items-->


@stop
