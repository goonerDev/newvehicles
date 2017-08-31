<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title><?php echo ucfirst( strtolower( $part_type ) ); ?> <?php echo ucfirst( strtolower( $sub_parts)); ?> | Car Panels | Car Parts</title>
<link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png">

<!--[if IE]><link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico"><![endif]-->
<meta name="description" content="We stock New car parts specializing in Replacement <?php echo ucfirst( strtolower( $part_type ) ); ?><?php echo ucfirst( strtolower($sub_keywords)); ?> we also stock car panels, car lamps and car parts "/>
<meta name="keywords" content="<?php echo ucfirst( strtolower( $part_type ) ); ?><?php echo ucfirst( strtolower($sub_keywords)); ?>" />

<link rel="stylesheet" href="http://newvehicleparts.co.uk/assets/nvp/css/bootstrap.css"/>
<link rel="stylesheet" href="http://newvehicleparts.co.uk/assets/nvp/css/style.css"/>
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon.png" rel="apple-touch-icon" />
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script>var host='<?php echo BASE_URL; ?>'</script>
<script>var cur_page='<?php echo @$cur_page; ?>'</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43976078-1', 'auto');
  ga('send', 'pageview');

</script></head>
<body>

<div id="outmost-top-wrapper">
	<div id="topmost" class="clearfix">
		<?php require_once 'menu.php'; ?>
	</div>
</div>

<div id="header-wrapper" class="clearfix">

	<div id="header-logo">
		<img src="<?php echo BASE_URL; ?>assets/<?php echo $view_dir; ?>/images/nvpo-logo.png"  alt="Crash Repair Parts - New Vehicle Parts Online" class="img-responsive">
	</div>

	<div id="header-contact-section">

		<p><span class="glyphicon glyphicon-earphone fs-14 hidden-xs"></span> <span class="fs-14 hidden-sm">+44 (0) 1405 810 876</span></p>

		<p><span class="glyphicon glyphicon-envelope fs-14 hidden-xs"></span> <span class="fs-14 hidden-sm">sales@newvehicleparts.co.uk</span></p>

	</div>

</div>
<div class="container">
			<div class="row">
					<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" style="margin-bottom:8px;" class="hidden-xs" alt="" />
			
				</div>
    <div class="row">
	          <h1>
	          <?php echo ucfirst( strtolower( $part_type ) ); ?><small><?php echo ucfirst( strtolower($sub_keywords)); ?></small>
	           </h1>
	          <p>
	          We stock New car parts specializing in Replacement <b><?php echo ucfirst( strtolower( $part_type ) ); ?><?php echo ucfirst( strtolower($sub_keywords)); ?></b></p>
              </div>

	<div clas="row">
		<?php if( !empty( $parts ) && is_array( $parts ) ): ?>
		
		
		<?php foreach( $parts as $line ): ?>
		
			<div class="col-xs-12 thumbnail" >
		
		<div class="col-xs-12 col-md-2"><?php if( $line[ 'product_image' ] == 'No' ): ?>
		<img src="http://www.newvehicleparts.co.uk/assets/nvp/images/no_image.jpg" class="img img-responsive center-block" <?php if(!empty($line[ 'group_sub_desc' ])): ?>
		alt="<?php echo rtrim($line['group_sub_desc'], 's');  ?>"
		<?php else: ?>
		alt="<?php echo rtrim($line['group_desc'], 'S');  ?>"
		<?php endif; ?> />
		
		<?php else: ?>
			<img src="http://www.qparts.co.uk/images/<?php echo $line[ 'sku' ]; ?>.jpg" class="img img-responsive center-block" <?php if(!empty($line[ 'group_sub_desc' ])): ?>
		alt="<?php echo rtrim($line['group_sub_desc'], 's');  ?>" 
		<?php else: ?>
		alt="<?php echo rtrim($line['group_desc'], 'S');  ?>"
		<?php endif; ?> />	
		
		<?php endif; ?>
		</div>
		<div class="col-xs-12 col-md-6">This
		<b>
		<?php if(!empty($line[ 'group_sub_desc' ])): ?>
		<?php echo rtrim($line['group_sub_desc'], 's');  ?> 
		<?php else: ?>
		<?php echo rtrim($line['group_desc'], 'S');  ?> 
		<?php endif; ?>
		</b> will fit <?php echo $line[ 'manufacture' ];  ?> <?php echo $line[ 'model' ];  ?>
		<?php echo $line[ 'start_year' ];  ?> to <?php echo $line[ 'end_year' ];  ?>
		</div>
		<div class="col-xs-12 col-md-4">
		<?php
		/*
		This section needs to echo out the following 
		Price 
		Add to cart button
		More info button
		*/
		?>
		<?php
		$manufacture_url = strtolower( misc::urlencode( $line[ 'manufacture' ] ) );
		$model_url = strtolower( misc::urlencode( $line[ 'model' ] ) );
		$href = BASE_URL.$manufacture_url.'/'.$model_url.'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) );
		$data_smap_node = $line [ 'product' ];
		?>
		<!-- Product name -->



<!-- Display price and add to cart button -->
<div id="<?php echo rand(); ?>">

	<?php 
	
	$price = misc::get_available_prices( @$line[ 'price' ], $user_no, $vat, 0 ); ?>

	<?php if( $price[ 'price' ] == E_CALL_FOR_PRICE ): ?>
	<p><strong>Price:</strong> <?php echo E_CALL_FOR_PRICE; ?></p>
	<?php else: ?>
	<p><strong>Price:</strong> <?php echo CURRENCY.number_format( @$price[ 'price' ], 2 ); ?>&nbsp;<span style="font-size:12px;">Inc.VAT</span></p>
	<?php endif; ?>

	<?php
	if( !empty( $vehic_will_fit ) ): // google PPC only at this moment
		$vehicles_will_fit =  Modules::run( 'catalog/ccatalog/vehicle_product_will_fit', $line[ 'sku' ], $manufacture_url, $model_url );

		if( is_array( $vehicles_will_fit ) ):
	?>
	<strong>This product fits:</strong>
	<p>
	<?php foreach( $vehicles_will_fit as $vehic ): ?>
		<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $vehic[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $vehic[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $vehic[ 'model_type' ] ) ); ?>" style="font-size:12px;">
		<?php echo $vehic[ 'model_type' ]; ?>
		</a><br />
	<?php endforeach; ?>
	</p>
	<?php
		endif;
	endif;
	?>
<?php if( $line[ 'stock' ] > 0 ): ?>
<!-- In stock 
	<p class="in-stock-label"><span class="label label-success" style="padding:5px;width:90%;">In stock</p> -->

<?php else: ?>
<!-- Out of stock -->
<?php endif; ?>
</div>
	<p class="btn-wrapper clear">
		<a href="<?php echo $href; ?>" class="btn <?php echo rand(); ?> btn-primary btn-sm bg-171f6a smap-node" data-smap-node="<?php echo $data_smap_node; ?>" data-smap-follow="0" data-smap-level="4">
			More Info
		</a>&nbsp;<?php if( $line[ 'stock' ] > 0 && $price[ 'price' ] != E_CALL_FOR_PRICE ): ?>
	
	<a href="<?php echo base_url(); ?>add-item/<?php echo $line[ 'sku' ]; ?>" class="add-to-cart btn <?php echo rand(); ?> btn-sm btn-success<?php if( $is_admin == 1 || @$price[ 'price' ] == 0 ) echo ' disabled'; ?>">
		<span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
	</a>

	<?php else: ?><span class="btn <?php echo rand(); ?> btn-danger btn-sm">Out of stock</span>

<?php endif; ?>

	</p>
		<?php //require 'prodcut-stok-level-new.php'; ?>
		</div>
		</div>
		<?php endforeach; ?>	
	</div>
	
	<div class="row">
		<div class="col-xs-12 center-block">
		<?php echo $pagination; ?>
		</div>
	</div>
	<?php else: ?>
	<div class="row" >
	<p></p>
	</div>
	
	<?php endif; ?>
</div> <!-- End Container -->	
<br class="clear"/><?php require_once 'footer.php';