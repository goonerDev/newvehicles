<?php require_once 'header.php'; ?>

<?php if( $vehicle != false ): ?>

<div class="content-section">

<?php /* Title */ ?>
<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">
	<span class="color-171f6a">Vehicle Registration</span> - <span class="color-grey1"><?php echo @$vehicle[ 'vrm' ]; ?></span>
</h1>

<div class="row">

	<div class="col-md-5">

		<p>
			<strong>Make:</strong> <?php echo @$vehicle[ 'make' ]; ?>
		</p>

		<p>
			<strong>Model:</strong> <?php echo @$vehicle[ 'model' ]; ?>
		</p>

		<p>
			<strong>Body style:</strong> <?php echo @$vehicle[ 'body_style' ]; ?>
		</p>

		<p>
		<strong>Registration Year:</strong> <?php echo @$vehicle[ 'date_first_registered' ]; ?>
		</p>

	</div> <!-- .col-md-5 -->

	<div class="col-md-5">

		<p>
			<strong>Variant:</strong> <?php echo @$vehicle[ 'variant_descr' ]; ?>
		</p>

		<p>
			<strong>Engine capacity:</strong> <?php echo @$vehicle[ 'engine_capacity' ]; ?>
		</p>

		<p>
			<strong>Fuel type:</strong> <?php echo @$vehicle[ 'fuel_type' ]; ?>
		</p>
		<p><a href="<?php echo BASE_URL; ?>search/process_vrm/q-<?php echo @$vehicle[ 'vrm' ].'/'.misc::urlencode( @$vehicle[ 'make' ] ).'/'.urlencode( @$vehicle[ 'model' ] ); ?>" class="btn btn-success">Find Parts for <?php echo @$vehicle[ 'vrm' ]; ?></a>
		</p>

	</div> <!-- .col-md-5 -->
</div> <!-- .row -->
<?php
$categories_exist = !empty( $categories );
?>
<div class="row">
	<!--<div id="product-list-cols-categories" data-target="vrm-result-list" class="ajax-loader-container <?php if( $categories_exist ): ?>col-xs-12 col-md-3<?php endif; ?>">-->
	<?php if( $categories_exist ): ?>
		<!--<ul>
	<?php foreach( $categories as $cat ): $cat_url = strtolower( misc::urlencode( $cat[ 'group_desc' ] ) ); ?>
			<li class="<?php echo ( $cat_url == $category_url ? 'active' : '' ); ?>"><a href="<?php echo BASE_URL; ?>search/process_vrm/q-<?php echo $vrm; ?>/<?php echo @$vehicle[ 'ktype' ]; ?>/<?php echo $cat_url; ?>"><?php echo ucfirst( strtolower( $cat[ 'group_desc' ] ) ); ?></a></li>
	<?php endforeach; ?>
		</ul>-->
	<?php endif; ?>
	<!--</div>--><!-- #product-list-cols-categories -->
	<!--<div id="product-list-cols-products" class="<?php if( $categories_exist ): ?>col-xs-12 col-md-9<?php endif; ?>">
		<fieldset>

			<legend style="padding-top:20px">
				Parts Available for <?php echo @$vehicle[ 'make' ]; ?> <?php echo @$vehicle[ 'model' ]; ?> <?php echo @$vehicle[ 'body_style' ]; ?>
				<p style="font-size:14px;color:red;"><b>If the part(s) you are looking for are not listed below, please use the Choose Make button above.</b></p>
			</legend>

			<div id="vrm-result-list">
		<?php if( $items != false ):

			$data = $items; // $data is the variable name within product-list-cols-ajax.php
			//require_once 'search-by-vrm-result-cols-list.php';

		?>
			</div>
		<?php else: ?>

		<p style="font-size:14px;color:red;"><b>If NO part(s) are found for your vehicle, please use the Choose Make button above.</b></p>

		<?php endif; ?>

		</fieldset>
	</div> --><!-- #product-list-cols-products -->
</div> <!-- .row -->

</div> <!-- .content-section -->

<?php endif; ?>

<?php require_once 'footer.php';