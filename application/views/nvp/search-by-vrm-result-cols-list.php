<ul>

<?php
foreach( $data as $line ):
	$href = BASE_URL.'vrm-search/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'item' ] ) ).'/'.$vehicle[ 'ktype' ];
	
?>
<li class="product-list-cols">

	<div class="product-list-cols-img-wrapper">

<?php if( $line[ 'product_image' ] == 'No' ): ?>

		<p class="img-wrapper"><img src="<?php echo BASE_URL; ?>assets/images/no_image.jpg" class="thumbnail thumbnail-235"></p>

<?php else: ?>

		<p class="img-wrapper" align="center"><img src="http://www.qparts.co.uk/images/<?php echo $line[ 'sku' ]; ?>.jpg" class="thumbnail thumbnail-235"/></p>

<?php endif; ?>

	</div>

	<div class="product-list-cols-details-wrapper">

<!-- Model fits -->
		<p><strong>Model fit: </strong><?php echo $line[ 'models_ktype_fits' ]; ?></p>

<!-- Product name -->
		<p class="pdt-name-row-wrapper"><span class="pdt-name-cell-wrapper"><?php echo $line [ 'item' ]; ?></span></p>

<!-- Product code -->
		<p><strong>Code: </strong><?php echo $line[ 'sku' ]; ?></p>

<!-- Certificate -->
		<p><strong>Certificate: </strong><?php echo $line[ 'certificate' ]; ?></p>

<!-- If connected user, display price and add to cart button -->
	<?php if( $connected_user ): ?>
	
		<div id="add-to-cart-<?php echo $line[ 'sku' ]; ?>"><?php require 'product-stock-level.php'; ?></div>
	
	<?php else: ?>

		<p class="btn-wrapper">
			<a href="<?php echo $href; ?>" class="btn btn-primary btn-sm bg-171f6a">
				View Item
			</a>
		</p>

	<?php endif; /* end if $connected_user */?>

	</div>

</li>
<?php endforeach; ?>

</ul>

<div class="ajax-loader-container centered" data-target="vrm-result-list"><?php echo $pagination; ?></div>