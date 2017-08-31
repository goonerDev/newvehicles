<?php if( is_array( $data ) ): ?>

<ul class="modeltype-html-list">

<?php foreach( $data as $s ): ?>
	<li>

		<a href="<?php echo base_url().$manufacture_url.'/'.$model_url.'/'.strtolower( misc::urlencode( $s[ 'model_type' ] ) ); ?>" class="start-end-year-item-list"><?php echo $s[ 'model_type' ]; ?>
		</a>

	</li>
<?php endforeach; ?>

</ul>

<?php endif; ?>