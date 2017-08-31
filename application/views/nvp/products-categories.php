<?php
if( substr( $modeltype_url, 0, 4 ) == 'cat-' )
	$base_url = BASE_URL.$manufacture_url.'/'.$model_url;
else
	$base_url = BASE_URL.$manufacture_url.'/'.$model_url.'/'.$modeltype_url;
?>
<ul class="category-html-list">

	<li><a href="<?php echo $base_url; ?>">All</a></li>
	<li><a href="<?php echo $base_url; ?>/certified">Certified parts</a></li>

	<?php foreach( $categories as $category ): ?>
	<?php $category_url = strtolower( misc::urlencode( $category[ 'group_desc' ] ) ); ?>
	<li>
		<a href="<?php echo $base_url; ?>/cat-<?php echo $category_url; ?>" class="category">
		<?php echo empty( $category[ 'group_desc' ] ) ? 'Misc' : ucfirst( strtolower( $category[ 'group_desc' ] ) ); ?></a>
	</li>

	<?php endforeach; ?>

</ul>
