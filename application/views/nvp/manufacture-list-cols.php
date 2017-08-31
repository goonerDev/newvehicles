<?php require_once 'header.php'; ?>

<div class="content-section">

<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" class="hidden-xs"  style="margin-bottom:8px;"  alt="Crash Repair Parts Specialists" />
<!-- Breadcrumb -->
<div class="row">
<div class="col-xs-12">
        <div class="btn-group btn-breadcrumb">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
             <a href="<?php echo BASE_URL; ?>" class="btn btn-info">All Manufacturers</a>

        </div>
        </div>
	</div>

<?php /* Title */ ?>
<h3><span class="hidden-xs" >New Vehicle Part -</span>Select Manufacturer</h3>
<p class="hidden-xs">At New Vehicle Parts, we have a large selection of <b>new car parts</b> to fit the maufacturers listed below, To find the correct <b>car parts</b> you need to select your car manutacturer below:</p>
<?php if( is_array( $manufactures ) ): ?>

<!-- Enumeration of models -->
<?php foreach( $manufactures as $manufacture ): ?>

<?php $manufacture_url = BASE_URL.strtolower( misc::urlencode( $manufacture[ 'manufacture' ] ) ); ?>
<div class="col-xs-12 <?php echo rand(); ?> col-md-4">
	<div class="<?php echo rand(); ?> thumbnail effect4">

		<p><a href="<?php echo $manufacture_url; ?>">
			<?php if( !empty( $manufacture[ 'manufacture_image' ] ) ): ?>
			<img src="<?php echo $manufacture_url.'-64.png'; ?>" class="img img-responsive" style="width:100px;"/>
			<?php else: ?>
			<img src="<?php echo BASE_URL; ?>assets/images/_no_image.jpg" class="img img-responsive" />
			<?php endif; ?>
		</a></p>
<p style="text-align:center"><a href="<?php echo $manufacture_url; ?>" class="<?php echo rand(); ?> btn btn-primary "><?php echo $manufacture[ 'manufacture' ]; ?></a></p>	
</div>
</div>
<?php endforeach; ?>



<?php endif; ?>
</div>

<div class="row"></div>
<?php require_once 'footer.php';