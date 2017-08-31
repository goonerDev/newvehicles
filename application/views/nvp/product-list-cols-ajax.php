<?php foreach( $data as $line ): ?>

<li class="product-list-cols <?php echo rand(); ?> effect4" style="border:1px solid #999;">

	<div class="<?php echo rand(); ?> product-list-cols-img-wrapper ">

<?php if( $line[ 'product_image' ] == 'No' ): ?>

		<img src="http://www.qpart.co.uk/_Live_Sites/manufactures/no_image.jpg"  alt="Replacement Car Parts for <?php echo ucfirst( strtolower($line[ 'manufacture' ])); ?> <?php echo ucfirst( strtolower($line[ 'model' ])); ?> <?php echo ucfirst( strtolower($line[ 'product' ])); ?>" class="thumbnail <?php echo rand(); ?> thumbnail-235">
<?php else: ?>
	<img src="http://www.qparts.co.uk/images/<?php echo $line[ 'sku' ]; ?>.jpg"  alt="Replacement Car Parts for <?php echo ucfirst( strtolower($line[ 'manufacture' ])); ?> <?php echo ucfirst( strtolower($line[ 'model' ])); ?> <?php echo ucfirst( strtolower($line[ 'product' ])); ?>" class="<?php echo rand(); ?> thumbnail thumbnail-235 zoomable" rel="zoom"/>

	

<?php endif; ?>

	</div>

	<div id="add-to-cart-<?php echo $line[ 'sku' ]; ?>" class="product-list-cols-details-wrapper <?php echo rand(); ?>">
	<?php require 'product-stock-level.php'; ?>
	</div>

</li>
<?php endforeach;