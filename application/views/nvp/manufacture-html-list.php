<div class="row">

<?php foreach( $manufactures as $manu ):
	$tmp_manu = strtolower( $manu[ 'manufacture' ] );
	if( trim( $manu[ 'manufacture' ] ) == ''
		// || $tmp_manu == 'consumables'
		// || $tmp_manu == 'universal'
		// || $tmp_manu == 'universal metal'
	)
		continue;
	$data_smap_node = ucwords( strtolower( $manu[ 'manufacture' ] ) );
?>
<div class="col-xs-12 col-md-3 img img-thumbnail center-block" style="margin:2px;text-align:center;padding:5px;">
	<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $manu[ 'manufacture' ] ) ); ?>" class="<?php echo rand(); ?> manufacture-item-list smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="1" data-smap-level="1" title="Replacement Car Parts for <?php echo $data_smap_node; ?> bonnets, <?php echo $data_smap_node; ?> front bumpers, <?php echo $data_smap_node; ?> wings, <?php echo $data_smap_node; ?> headlamps, <?php echo $data_smap_node; ?> fog lights, <?php echo $data_smap_node; ?>rear bumpers, <?php echo $data_smap_node; ?> rear lamps "><?php echo $data_smap_node; ?> Parts</a>
</div>

<?php endforeach; ?>

</div>