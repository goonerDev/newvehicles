<div style="padding:10px;">
<?php
	foreach( $items as $item ):
		if( strtolower( $item[ 'sku' ] ) == 'zcarriage' )
			continue;

?>
<li>

	<div class="frst"><img src="<?php echo ( $item[ 'product_image' ] == 'No' ? BASE_URL.'assets/'.$view_dir.'/images/no_image.jpg' : 'http://www.qparts.co.uk/images/'.$item[ 'sku' ].'.jpg' ); ?>" width="50" height="50"/></div>

	<div class="scnd">
		<span>
			<strong><?php echo $item[ 'product' ]; ?></strong><br/>
			<strong>Price:</strong> <?php echo CURRENCY.( number_format( $item[ 'pu' ], 2 ) ); ?><br/>
			<strong>Qty:</strong> <?php echo $item[ 'qty' ]; ?>
		</span>
	</div>

	<div class="thrd"><a href="<?php echo BASE_URL; ?>cart/delete_item/<?php echo $item[ 'id' ]; ?>" class="mini-cart-delete"><span class="glyphicon glyphicon-trash fs-12"></span></a></div>

	<hr class="clear"/>
</li>
<?php endforeach; ?>

<?php $current_shipping = Modules::run( 'cart/get_current_shipping_flate_rate', $cart_id, $user_no ); ?>
<div class="clearfix">
<!--<p class="pull-right">
<strong>Shipping:</strong> 
<?php echo empty( $current_shipping ) ? 'calculated at checkout' : CURRENCY.($current_shipping[ 'pu' ] ); ?>
</p>-->
</div>
<a href="#" id="mini-cart-close-btn" class="btn btn-primary pull-left" style="margin:15px 5px 5px 0;">Continue shopping</a>
	<a href="<?php echo BASE_URL; ?>checkout" id="mini-cart-checkout-btn" class="btn btn-success pull-right" style="margin:15px 5px 5px 0;color:#fff;">Checkout</a>

</div>