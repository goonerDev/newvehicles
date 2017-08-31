<?php require_once 'header.php'; ?>
<div class="content-section">

<?php // Banner ?>
<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" style="margin-bottom:8px;" class="hidden-xs" alt="Replacement Car Parts for <?php echo ucwords(strtolower($manufacture_name)); ?> <?php echo ucwords(strtolower($model_name)); ?> <?php echo $model_type_name; ?> - New Vehicle Parts Online" >

<div class="row">

        <div class="btn-group btn-breadcrumb">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
            <a href="<?php echo BASE_URL; ?>make" class="btn btn-primary hidden-xs">All Manufacturers</a>
            <a href="<?php echo BASE_URL.$manufacture_url; ?>" class="btn btn-primary hidden-xs" title="Replacement Car Parts for <?php echo ucwords(strtolower($manufacture_name)); ?>"><?php echo ucwords(strtolower($manufacture_name)); ?></a>
            <a href="<?php echo BASE_URL.$manufacture_url.'/'.$model_url; ?>" class="btn btn-primary hidden-xs" title="Replacement Auto Parts for <?php echo ucwords(strtolower($manufacture_name)); ?> <?php echo ucwords(strtolower($model_name)); ?>" ><?php echo ucwords(strtolower($model_name)); ?></a>
            <a href="<?php echo BASE_URL.$manufacture_url.'/'.$model_url.'/'.$model_type_url; ?>" class="btn btn-primary" title="Car Parts for <?php echo ucwords(strtolower($manufacture_name)); ?> <?php echo ucwords(strtolower($model_name)); ?> <?php echo $model_type_name; ?> UK based"><?php echo $model_type_name; ?></a>


<?php
if( !empty( $category_name ) ): ?>
	<a href="#" class="btn btn-info"><?php echo $category_name[ 'group_desc' ]; ?></a>
<?php else: ?>
<a href="#" class="btn btn-info">All</a>
<?php endif; ?>

        </div>
	</div>

<?php /* Title */ ?>
<div><h1><?php echo ucwords(strtolower($model_type_name)); ?> Car Parts</h1>
	<p>We've found the following Replacement Car Parts for <b><?php echo ucwords(strtolower($model_type_name)); ?></b>

</div>
<?php
if( !empty( $model_type_url ) )
	$base_url = BASE_URL.$manufacture_url.'/'.$model_url.'/'.$model_type_url;
else
	$base_url = BASE_URL.$manufacture_url.'/'.$model_url;
?>

<div id="product-list-cols-ajax-wrapper">
<?php if( !empty( $data ) && is_array( $data ) ): ?>

<ul>

<?php require_once 'product-list-cols-ajax.php'; ?>

</ul>

<div class="centered"><?php echo $pagination; ?></div>

<?php else: ?>

<p class="alert alert-danger">Empty</p>

<?php endif; ?>
</div>

<!-- left-side menu, product category -->
<div id="product-list-cols-category-filter">
	<div>
	<h2 class="<?php echo ( empty( $category_url ) || $category_url == 'cat-all' ? 'active' : '' ); ?>" data-url="<?php echo $base_url; ?>/cat-all">All</h2>
	<div></div>
	<?php foreach( $categories as $category ): ?>
	<?php $iter_category_url = 'cat-'.strtolower( misc::urlencode( $category[ 'group_desc' ] ) ); ?>
	<h2 class="<?php echo ( $iter_category_url == $category_url ? 'active' : '' ); ?>" data-url="<?php echo $base_url.'/'.$iter_category_url; ?>" title="Replacement Car Parts<?php echo ucwords(strtolower($model_name)); ?> <?php echo ucfirst( strtolower( $category[ 'group_desc' ] ) ); ?>"><?php echo ucwords(strtolower($model_name)); ?> <?php echo ucfirst( strtolower( $category[ 'group_desc' ] ) ); ?></h2>
	<div></div>
	<?php endforeach; ?>
	</div>
	
	
<div></div><div></div>	
</div>
<div></div>
<p><script src="//dash.reviews.co.uk/widget/badge_popup.js"></script><div data-badge="18356"> <iframe id="custom-2" frameborder="0" scrolling="no" style="border:0px;height:140px;width:200px;margin-top:10px;overflow:none;" src="https://secure.reviews.co.uk/badge/mini/18356?colour1=79D236&colour2=4BA609"></iframe></p>
<h4>Parts Guide</h4>
			<p style="font-weight:normal;font-size:12px;">Make sure you order the correct part(S)</p>
				<p class="small" ><strong>L/H</strong> = Near side (Passenger Side)</p>
				<p class="small" ><strong>R/H</strong> = Offside (Drivers Side)</p>	
				<p></p>
                               <h4>Colour Matching</h4>
<p>We Don't offer colour matched parts, only primed and non-primed parts, unless otherwise stated</p>
<p>

</div>

</div>	
<br class="clear"/><?php require_once 'footer.php';