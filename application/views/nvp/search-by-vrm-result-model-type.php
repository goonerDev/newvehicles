<?php require_once 'header.php'; ?>

<div class="content-section">

<?php /* Title */ ?>

<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">

	<img src="http://www.qpart.co.uk/_Live_Sites/manufactures/<?php echo $manufacture_url.'.png'; ?>"/>&nbsp;

	<span class="color-171f6a"><?php echo $model_name; ?></span> - <span class="color-grey1">Year Range</span>

</h1>
	

<?php /* Breadcrumb */ ?>

<h3 class="bread-crumb">

	<a href="<?php echo BASE_URL; ?>" class="color-171f6a">
		Home
	</a>&nbsp;&gt;&nbsp;

	<a href="<?php echo BASE_URL.$manufacture_url; ?>" class="color-171f6a">
		<?php echo $manufacture_name; ?>
	</a>&nbsp;&gt;&nbsp;

	<?php echo $model_name; ?>
</h3>
<?php if( is_array( $start_end_years ) ) : ?>

<ul class="clearfix">

<?php foreach( $start_end_years as $start_end_year ): ?>
<?php $data_smap_node = $start_end_year[ 'model_type' ]; ?>

	<li class="model-list-cols">

	<?php if( empty( $start_end_year[ 'model_image' ] ) ): ?>
		<p align="center">
			<img src="http://www.qpart.co.uk/_Live_Sites/manufactures/no_image.jpg" class="thumbnail thumbnail-180"/>
		</p>
	<?php else: ?>
		<p align="center">
			<img src="http://www.qparts.co.uk/images/<?php echo $start_end_year[ 'model_image' ]; ?>" alt="<?php echo $start_end_year[ 'model_type' ]; ?>" class="thumbnail thumbnail-180"/>
		</p>

<?php endif; ?>

	<p class="height-45">
		<?php echo $data_smap_node; ?>
	</p>

	<p>
		<a href="<?php echo BASE_URL.$manufacture_url; ?>/<?php echo strtolower( $model_url ); ?>/<?php echo strtolower( misc::urlencode( $start_end_year[ 'model_type' ] ) ); ?>" class="btn btn-primary bg-171f6a smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="1" data-smap-level="3">Select Year</a>
	</p>

	</li>

<?php endforeach; ?>

</ul>

<?php endif; ?>
</div><!--

--><?php require_once 'footer.php';