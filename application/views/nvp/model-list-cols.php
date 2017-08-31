<?php require_once 'header.php'; ?>

<div class="content-section">

<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" class="hidden-xs" style="margin-bottom:8px;" alt="Crash Repair Parts Specialists" />
<!-- Breadcrumb -->
<div class="row">

        <div class="btn-group btn-breadcrumb">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
            <a href="<?php echo BASE_URL; ?>make" class="btn btn-primary hidden-xs">All Manufacturers</a>
            <a href="#" class="btn btn-info"><?php echo ucwords(strtolower($manufacture_name)); ?></a>
        </div>
	</div>

<?php /* Title */ ?>
<div>
<h1 class="hidden-xs" title="<?php echo ucwords(strtolower($manufacture_name)); ?> Car Parts UK">
	<img src="<?php echo BASE_URL.$manufacture_url.'-64.png'; ?>" alt="Replacement Car Parts UK for <?php echo $manufacture_name; ?>" />
	<?php echo ucwords(strtolower($manufacture_name)); ?> Replacement Car Parts
</h1>
</div>
<p>Find <b>replacement <?php echo ucwords(strtolower($manufacture_name)); ?> car parts</b> for your<b> <?php echo ucwords(strtolower($manufacture_name)); ?></b>, Please select your <b><?php echo ucwords(strtolower($manufacture_name)); ?></b> model below to find your <b>replacement <?php echo ucwords(strtolower($manufacture_name)); ?> car parts</b>:</p>
<br class="clear"/>

<?php if( is_array( $data ) ): ?>


<!-- Enumeration of models -->
<?php foreach( $data as $model ): ?>
<div class="<?php echo rand(); ?> clo-xs-12 col-md-4 ">
<div class="thumbnail <?php echo rand(); ?> effect4">

	<p style="text-align:center" title="Auto Parts UK for <?php echo ucwords(strtolower($manufacture_name)); ?> <?php echo $model[ 'model' ]; ?>" ><?php echo $model[ 'model' ]; ?></p>

	<p style="text-align:center"><a href="<?php echo BASE_URL.$manufacture_url; ?>/<?php echo strtolower( misc::urlencode( str_replace( '-', '', $model[ 'model' ] ) ) ); ?>" class="btn <?php echo rand(); ?> btn-primary bg-171f6a smap-node" data-smap-node="<?php echo $model[ 'model' ]; ?>" data-smap-follow="1" data-smap-level="2" title="Online Replacement Car Parts for <?php echo ucwords(strtolower($manufacture_name)); ?> <?php echo $model[ 'model' ]; ?> UK">Select Model</a></p>
</div>
</div>

<?php endforeach; ?>



<?php endif; ?>

</div>
<div class="row"></div>
<?php require_once 'footer.php';