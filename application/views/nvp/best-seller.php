<h4>Popular Searches</h4>

<?php if( is_array( $data ) ): ?>

<ul class="content-section best-seller-content-section">

<?php foreach( $data as $line ): ?>

<li class="product-list-cols effect4" style="border:1px solid #999;">
<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $line[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) ); ?>" title="New <?php echo $line[ 'product' ]; ?> for <?php echo $line[ 'manufacture' ]; ?> <?php echo $line[ 'model' ]; ?>">
	<div class="pdt-manufacture-wrapper clearfix">
		<div class="pdt-manufacture-name pull-left">
			<?php echo $line[ 'manufacture' ]; ?> <?php echo $line[ 'model' ]; ?>			
		</div>
	</div>

	<div class="pdt-name-row-wrapper">
		<?php echo $line[ 'product' ]; ?>
	</div>
</a>
	<div class="product-list-cols-img-wrapper">

	<?php if( $line[ 'product_image' ] == 'No' ): ?>

		<p class="img-wrapper"><img src="<?php echo BASE_URL; ?>assets/<?php echo $view_dir; ?>/images/no_image.jpg" class="thumbnail thumbnail-235" alt="<?php echo $line[ 'product' ]; ?>"></p>

	<?php else: ?>

		<p class="img-wrapper"><img src="http://www.qparts.co.uk/images/<?php echo $line[ 'sku' ]; ?>.jpg" class="thumbnail thumbnail-235" alt="<?php echo $line[ 'product' ]; ?>"></p>

	<?php endif; ?>

	</div>

	<p>Certificate: <strong><?php echo $line[ 'certificate' ]; ?></strong></p>

	<?php $price = misc::get_available_prices( @$line[ 'price' ], $user_no, $vat, 0 ); ?>

	<p>Price: <strong><?php echo $price[ 'price' ]; ?></strong></p>

	<p class="btn-wrapper">
		<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $line[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) ); ?>" class="btn btn-primary btn-sm">
			View Item
		</a><!--
	-->&nbsp;<!--

--><?php if( $line[ 'stock' ] > 0 ): ?>
	
	<a href="<?php echo BASE_URL; ?>add-item/<?php echo $line[ 'sku' ]; ?>" class="add-to-cart btn btn-sm btn-success<?php if( $is_admin == 1 || @$price[ 'price' ] == 0 ) echo ' disabled'; ?>">
		<span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
	</a>

	<?php else: ?><!--

--><span class="btn btn-danger btn-sm">Out of stock</span>

	<?php endif; ?>

	</p>

</li>

<?php endforeach; ?>

</ul>

<?php endif;