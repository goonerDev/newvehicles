<?php require_once 'header.php'; ?>
<div class="content-section">

<?php // Banner ?>
<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" style="margin-bottom:8px;" class="hidden-xs" alt="Car Parts Online UK - Crash Repair Parts Specialists" />

<?php /* Breadcrumb */ ?>
 <div class="btn-group btn-breadcrumb">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary" title="New Vehicle Parts UK Online Car Parts Suppliers"><i class="glyphicon glyphicon-home"></i></a>
            <a href="<?php echo BASE_URL; ?>make" class="btn btn-primary hidden-xs" title="Car Parts UK">All Manufacturers</a>
            <a href="<?php echo BASE_URL.$manufacture_url; ?>" class="btn btn-primary" title="Replacement Car Parts UK for <?php echo ucwords(strtolower($manufacture_name)); ?>"><?php echo ucwords(strtolower($manufacture_name)); ?></a>
            <a href="#" class="btn btn-info" title="Online Replacement Auto Parts UK for <?php echo ucwords(strtolower($manufacture_name)); ?> <?php echo ucwords(strtolower($model_name)); ?>"> <?php echo ucwords(strtolower($model_name)); ?></a>
</div>
</div>

<?php /* Title */ ?>
<div>
	<h3 class="hidden-xs">
		<img src="<?php echo BASE_URL.$manufacture_url.'-64.png'; ?>" alt="Replacement Auto Parts for <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?> " />&nbsp;
		<strong>Find Replacement Car Parts for <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?> </strong>
	</h3>
	
	<?php if( empty( $google_ppc ) ): ?>
		<span>
		To make sure you find the correct <b>Replacement <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?> Car Parts for your <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?></b> , Please select the year range of your <b><?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?></b> below:
		</span>
		<?php else: ?>
		<span>To find <strong><?php echo ucfirst( strtolower( @$category_name[ 'group_desc' ] ) ); ?></strong> that fit your <strong><?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?></strong>, select your car from below</span>
		<?php endif; ?>
	
</div>
<br class="clear"/>

<?php if( is_array( $data ) ) : ?>


<?php foreach( $data as $start_end_year ): ?>
<?php $data_smap_node = $start_end_year[ 'model_type' ]; ?>

	<div class="col-xs-12 col-md-4">
			<div class="thumbnail effect4">
			<p style="text-align:center" title="Replacement Auto Parts for <?php echo ucwords(strtolower( $start_end_year[ 'model_type' ])); ?>" ><b><?php echo ucwords(strtolower( $start_end_year[ 'model_type' ])); ?></b></p>

	<?php if( empty( $start_end_year[ 'model_image' ] ) ): ?>

		<p><a href="<?php echo BASE_URL.$manufacture_url; ?>/<?php echo $model_url; ?>/<?php echo strtolower( misc::urlencode( $start_end_year[ 'model_type' ] ) ).( !empty( $google_ppc ) ? '/'.$category_url : '' ); ?>" class="smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="1" data-smap-level="3" title="<?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?> Replacement Car Parts UK">	<img src="<?php echo BASE_URL; ?>assets/<?php echo $view_dir; ?>/images/_no_image.jpg" class="img img-responsive hidden-xs" style="max-height:100px" alt="UK Replacement Car Parts for <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?>"  /></a></p>

	<?php else: ?>

		<p><a href="<?php echo BASE_URL.$manufacture_url; ?>/<?php echo $model_url; ?>/<?php echo strtolower( misc::urlencode( $start_end_year[ 'model_type' ] ) ).( !empty( $google_ppc ) ? '/'.$category_url : '' ); ?>" class="smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="1" data-smap-level="3" title="<?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?> Replacement Car Parts UK">	<img src="http://www.qparts.co.uk/images/<?php echo $start_end_year[ 'model_image' ]; ?>" alt="<?php echo ucfirst( strtolower( $start_end_year[ 'model_type' ])); ?>" class="img img-responsive hidden-xs" style="max-height:100px" alt="UK Replacement Car Parts for <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?>" /></a></p>

<?php endif; ?>


<div> 
		<p style="text-align:center"><a href="<?php echo BASE_URL.$manufacture_url; ?>/<?php echo $model_url; ?>/<?php echo strtolower( misc::urlencode( $start_end_year[ 'model_type' ] ) ).( !empty( $google_ppc ) ? '/'.$category_url : '' ); ?>" class="btn btn-primary bg-171f6a smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="1" data-smap-level="3" title="Find <?php echo ucfirst( strtolower( $manufacture_name ) ); ?> <?php echo ucfirst( strtolower( $model_name ) ); ?> Replacement Car Parts UK">Select <?php if( $manufacture_url == 'consumables'  ): ?>Consumable<?php elseif( $manufacture_url == 'universal' || $manufacture_url == 'universalmetal' ): ?>Metal<?php else: ?><?php echo ucwords(strtolower( $start_end_year[ 'model_type' ])); ?><?php endif; ?></a></p>

   </div>
	</div>
</div>
<?php endforeach; ?>
</div>

<?php endif; ?>
</div>
</div>
<div class="row"></div><!--

--><?php require_once 'footer.php';