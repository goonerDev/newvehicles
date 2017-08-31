<div class="yamm-content">

<div class="row">

<?php
$n = count( $data );

if( $n > 0 ) {
	array_unshift( $data, array( 'model_type' => '..' ) );
	$n++;
}

$per_col = ceil( $n/4 );

$manufacture = strtolower( misc::urlencode( $manufacture ) );
$model = strtolower( misc::urlencode( $model ) );

for( $i = 0, $j = 0; $i < $n; $i++, $j++ ):
?>

	<?php if( $j == 0 ): ?><ul class="col-sm-3"><?php endif;
		$class = '';

		if( $data[ $i ][ 'model_type' ] == '..' ) {
			$url = base_url().$manufacture;
			$class = 'menu-item-list';
		}
		else
			$url = base_url().$manufacture.'/'.$model.'/'.strtolower( misc::urlencode( $data[ $i ][ 'model_type' ] ) );
	?>
<li>

	<a href="<?php echo $url; ?>" class="<?php echo $class; ?>"><?php echo ucwords( strtolower($data[ $i ][ 'model_type' ] ) ); ?></a>

</li>

	<?php if( $j == $per_col - 1 ): ?></ul><?php $j = -1; endif; ?>

<?php endfor; ?>

<?php if( 0 < $j && $j <= $per_col -1 ): ?></ul><?php endif; ?>

</div>

</div>