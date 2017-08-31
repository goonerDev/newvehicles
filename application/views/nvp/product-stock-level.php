<?php
	$manufacture_url = strtolower( misc::urlencode( $line[ 'manufacture' ] ) );
	$model_url = strtolower( misc::urlencode( $line[ 'model' ] ) );
	$href = BASE_URL.$manufacture_url.'/'.$model_url.'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) );

	$data_smap_node = $line [ 'product' ];
?>

<!-- Product name -->

	<p class="pdt-name-row-wrapper"><span class="pdt-name-cell-wrapper" title=" <?php echo ucfirst( strtolower($line[ 'manufacture' ])); ?> <?php echo ucfirst( strtolower($line[ 'model' ])); ?> <?php echo $data_smap_node; ?> - Car Parts UK"><strong><?php echo $data_smap_node; ?></strong></span></p>
	<p><strong>Code: </strong><?php echo $line[ 'sku' ]; ?></p>

<!-- Certificate -->
	<p><strong>Certificate: </strong><?php echo ucfirst( strtolower( $line[ 'certificate' ] ) ); ?></p>

<!-- Display price and add to cart button -->
<div id="<?php echo rand(); ?>">

	<?php $price = misc::get_available_prices( @$line[ 'price' ], $user_no, $vat, 0 ); ?>

	<?php if( $price[ 'price' ] == E_CALL_FOR_PRICE ): ?>
	<p><strong>Price:</strong> <?php echo E_CALL_FOR_PRICE; ?></p>
	<?php else: ?>
	<p><strong>Price:</strong> <?php echo @$price[ 'price' ]; ?>&nbsp;<span style="font-size:12px;">Inc.VAT</span></p>
	<?php endif; ?>

	<?php
	if( !empty( $vehic_will_fit ) ): // google PPC only at this moment
		$vehicles_will_fit =  Modules::run( 'catalog/ccatalog/vehicle_product_will_fit', $line[ 'sku' ], $manufacture_url, $model_url );

		if( is_array( $vehicles_will_fit ) ):
	?>
	<strong>This product fits:</strong>
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
<?php if( $line[ 'stock' ] > 0 ): ?>

<!-- In stock 
	<p class="in-stock-label"><span class="label label-success" style="padding:5px;width:90%;">In stock</p> -->
	<p>&nbsp;</p>

<?php else: ?>

<!-- Out of stock -->
	<p><strong>Due in stock: <?php if( !empty( $line[ 'due_date' ] ) ): echo $line[ 'due_date' ]; else: echo '</strong>'.E_CALL_DUE_DATE_EMPTY; endif; ?></p>
<?php endif; ?>
</div>
	<p class="btn-wrapper clear">
		<a href="<?php echo $href; ?>" class="btn <?php echo rand(); ?> btn-primary btn-sm bg-171f6a smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="0" data-smap-level="4" title="<?php echo ucfirst( strtolower($line[ 'manufacture' ])); ?> <?php echo ucfirst( strtolower($line[ 'model' ])); ?> Replacement <?php echo $data_smap_node; ?>" >
			More Info
		</a><!--
	-->&nbsp;<!--

--><?php if( $line[ 'stock' ] > 0 && $price[ 'price' ] != E_CALL_FOR_PRICE ): ?>
	
	<a href="<?php echo base_url(); ?>add-item/<?php echo $line[ 'sku' ]; ?>" class="add-to-cart btn <?php echo rand(); ?> btn-sm btn-success<?php if( $is_admin == 1 || @$price[ 'price' ] == 0 ) echo ' disabled'; ?>" title="<?php echo ucfirst( strtolower($line[ 'manufacture' ])); ?> <?php echo ucfirst( strtolower($line[ 'model' ])); ?> Replacement <?php echo $data_smap_node; ?>">
		<span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
	</a>

	<?php else: ?><!--

--><span class="btn <?php echo rand(); ?> btn-danger btn-sm" title="<?php echo ucfirst( strtolower($line[ 'manufacture' ])); ?> <?php echo ucfirst( strtolower($line[ 'model' ])); ?> Replacement <?php echo $data_smap_node; ?>">Out of stock</span>

<?php endif; ?>

	</p>