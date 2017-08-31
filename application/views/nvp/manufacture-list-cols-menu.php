<?php
$n = count( $manufactures );

if( $n > 0 ):

for( $i = 0, $j = 0; $i < $n; $i++, $j++ ):
	$tmp_manu = strtolower( $manufactures[ $i ][ 'manufacture' ] );
	// if( $tmp_manu == 'consumables' ||
		// $tmp_manu == 'universal' ||
		// $tmp_manu == 'universal metal' ) {
		// $j--;
		// continue;
	// }
?>

<li>
	<a href="<?php echo base_url().strtolower( misc::urlencode( $manufactures[ $i ][ 'manufacture' ] ) ); ?>" class="" title="Replacement car parts for <?php echo ucwords( strtolower($manufactures[ $i ][ 'manufacture' ] ) ); ?> all Models"><?php echo ucwords( strtolower($manufactures[ $i ][ 'manufacture' ] ) ); ?></a>
</li>

<?php
endfor;
endif;