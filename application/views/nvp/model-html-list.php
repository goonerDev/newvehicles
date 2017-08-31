<?php $n = count( $data ); ?>

<ul class="model-html-list">

<?php for( $i = 0; $i < $n; $i++ ): ?>
	<?php if( trim( $data[ $i ][ 'model' ] ) == '' ) continue; ?>
<li>
	<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $data[ $i ][ 'manufacture' ] ) ).'/'.strtolower( str_replace( '-', '', misc::urlencode( $data[ $i ][ 'model' ] ) ) ); ?>" class="model-item-list"><?php echo ucwords( strtolower($data[ $i ][ 'model' ] ) ); ?></a>
</li>

<?php endfor; ?>

</ul>
