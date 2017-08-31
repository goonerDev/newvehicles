<ul class="side-html-list">
	<?php foreach( $sides as $side ): ?>
	<?php $iter_side_url = strtolower( misc::urlencode( $side[ 'side' ] ) ); ?>
	<li>
		<a href="<?php echo $base_url; ?>/<?php echo $iter_side_url; ?>"><?php echo ucfirst( strtolower( $side[ 'side' ] ) ); ?></a>
	</li>
	<?php endforeach; ?>
</ul>