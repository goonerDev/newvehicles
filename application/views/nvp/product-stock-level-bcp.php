<?php
	$manufacture_url = strtolower( misc::urlencode( $line[ 'manufacture' ] ) );
	$model_url = strtolower( misc::urlencode( $line[ 'model' ] ) );
	$href = BASE_URL.$manufacture_url.'/'.$model_url.'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) );

	$data_smap_node = $line [ 'product' ];
?>


<?php if( $line[ 'stock' ] > 0 ): ?>

<!-- In stock -->
	<p class="in-stock-label"><span class="label label-success" style="padding:5px;width:90%;">In stock</p>

<?php else: ?>

<!-- Out of stock -->
	<p class="in-stock-label">
		<span class="label label-danger" style="padding:5px;width:90%;">Out of stock</span><br/>
		<?php if( !empty( $line[ 'due_date' ] ) ): ?><strong>Due in stock: <?php echo $line[ 'due_date' ]; ?></strong><?php endif; ?>
	</p>

<?php endif; ?>

<!-- Product name -->

	<p class="pdt-name-row-wrapper"><span class="pdt-name-cell-wrapper"><?php echo $data_smap_node; ?></span></p>

<!-- Product code -->
	<p><strong>Code: </strong><?php echo $line[ 'sku' ]; ?></p>

<!-- Certificate -->
	<p><strong>Certificate: </strong><?php echo $line[ 'certificate' ]; ?></p>

<!-- Display price and add to cart button -->
<div>

	<?php $price = misc::get_available_prices( @$line[ 'price' ], $user_no, $vat, @$line[ 'ebay_carriage' ] ); ?>

	<p>Price: <strong><?php echo CURRENCY.number_format( @$price[ 'price' ], 2 ); ?></strong>&nbsp;<span style="font-size:12px;">Inc.VAT</span></p>

	<?php
	if( !empty( $vehic_will_fit ) ): // google PPC only at this moment
		$vehicles_will_fit =  Modules::run( 'catalog/ccatalog/vehicle_product_will_fit', $line[ 'sku' ], $manufacture_url, $model_url );

		if( is_array( $vehicles_will_fit ) ):
	?>
	<strong>Vehicles this product fits:</strong>
	<p>
	<?php foreach( $vehicles_will_fit as $vehic ): ?>
		<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $vehic[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $vehic[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $vehic[ 'model_type' ] ) ); ?>" style="font-size:12px;">
		<?php echo $vehic[ 'model_type' ]; ?>
		</a><br />
	<?php endforeach; ?>
	</p>
	<?php
		endif;
	endif;
	?>

	<p class="btn-wrapper">
		<a href="<?php echo $href; ?>" class="btn btn-primary btn-sm bg-171f6a smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="0" data-smap-level="4">
			View Item
		</a><!--
	-->&nbsp;<!--

--><?php if( $line[ 'stock' ] > 0 ): ?>
	
	<a href="<?php echo base_url(); ?>add-item/<?php echo $line[ 'sku' ]; ?>" class="add-to-cart btn btn-sm btn-success<?php if( $is_admin == 1 || @$price[ 'price' ] == 0 ) echo ' disabled'; ?>">
		<span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
	</a>

	<?php else: ?><!--

--><span class="btn btn-danger btn-sm">Out of stock</span>

<?php endif; ?>

	</p>

</div>