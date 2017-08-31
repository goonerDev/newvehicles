<?php
$n = count( $consumables[ 'data' ] );

if( $n > 0 ):

$base_url = BASE_URL.$consumables[ 'manufacture_url' ].'/'.$consumables['model_url' ];
$consumables = $consumables[ 'data' ];
for( $i = 0, $j = 0; $i < $n; $i++, $j++ ):
?>
<li>

	<a href="<?php echo $base_url.'/'.strtolower( misc::urlencode( $consumables[ $i ][ 'model_type' ] ) ); ?>" class="" title="Buy Quality <?php echo ucwords( strtolower( $consumables[ $i ][ 'model_type' ] ) ); ?> online"><?php echo ucwords( strtolower( $consumables[ $i ][ 'model_type' ] ) ); ?></a>

</li>

<?php
endfor;
endif;
