<div id="mini_cart" class="cart">
	<div class="cart-showcase pull-right">
		<i class="icon icon-ShoppingCart"></i>
		<span>Cart<?php if (! empty($online_order_items)) : ?> - <?php endif;?></span>
		<span class="cart-list-button"><?php if (! empty($online_order_items)) : ?><?php echo count($online_order_items);?><?php else:?>0<?php endif;?></span>
		
	</div>
	<?php if (!$is_empty_online_order) : ?>
	<!-- product cart list -->
	<div class="popup-cart-list">
		<ul class="list-unstyled">
			<?php $i = 0; foreach($online_order_items as $row) { $i++; ?>
			<li class="alert">
				<div class="media-left">
					<?php /*<img src="images/product/popup/17.jpg" alt="product">*/?>
				</div>
				<div class="media-body">
					<h6 class="product-title"><?php echo $row->name;?></h6>
					<span class="price"><?php echo $currency->symbol . ' ' . $row->price;?> X <?php echo $row->quantity;?></span>
					<a href="{{ url('online-order/delete-cart') . '/' . $row->id }}" class="close"><i class="fa fa-trash-o"></i></a>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php /*<div class="total-price">
			<span class="screen-text">Total</span>
			<span class="price"><?php echo $currency->symbol . ' ' . \Cart::getTotal();?></span>
		</div>*/?>
		<div class="cart-footer">
			<a href="{{ url('online-order/cart') }}" class="btn-cart">VIEW CART</a>
			<a href="{{ url('online-order/checkout') }}" class="btn-checkout">CHECKOUT</a>
		</div>
	</div>
	<?php endif;?>
</div>