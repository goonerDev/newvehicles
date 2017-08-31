<div class="yamm-content">

	<div class="row">

<?php
$n = count( $data );

if( $n > 0 ) {
	array_unshift( $data, array( 'model' => '..' ) );
	$n++;
}

$per_col = ceil( $n/4 );

$manufacture = strtolower( misc::urlencode( $manufacture ) );

for( $i = 0, $j = 0; $i < $n; $i++, $j++ ):
?>

	<?php if( $j == 0 ): ?><ul class="col-sm-3"><?php endif;

		if( $data[ $i ][ 'model' ] == '..' )
			$url = base_url().'fcatalog/manufactures';
		else
			$url = base_url().$manufacture.'/'.strtolower( misc::urlencode( $data[ $i ][ 'model' ] ) );
	?>
<li>

	<a href="<?php echo $url; ?>" class="menu-item-list"><?php echo ucwords( strtolower($data[ $i ][ 'model' ] ) ); ?></a>

</li>

	<?php if( $j == $per_col - 1 ): ?></ul><?php $j = -1; endif; ?>

<?php endfor; ?>

<?php if( 0 < $j && $j <= $per_col -1 ): ?></ul><?php endif; ?>

</div>

</div>