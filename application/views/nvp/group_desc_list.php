<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>Replacement <?php echo ucfirst( strtolower( $part_type ) ); ?> New<?php echo ucfirst( strtolower( $sub_parts)); ?> - Buy <?php echo ucfirst( strtolower( $part_type ) ); ?> </title>
<link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png">

<!--[if IE]><link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico"><![endif]-->

<meta name="description" content="New Vehicle parts specilize in Replacement <?php echo ucfirst( strtolower( $part_type ) ); ?><?php echo ucfirst( strtolower($sub_keywords)); ?> for Ford focus, feista, BMW, Audi Buy Now."/>
<meta name="keywords" content="Replacement <?php echo ucfirst( strtolower( $part_type ) ); ?><?php echo ucfirst( strtolower($sub_keywords)); ?>" />

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
					<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" style="margin-bottom:8px;" class="hidden-xs" alt="Replacement <?php echo ucfirst( strtolower( $part_type ) ); ?>" />
			
				</div>
    <div class="row">
	          <h1>Replacement <?php echo ucfirst( strtolower( $part_type ) ); ?></h1>
	          <p>New Vehicle parts stock a wide range of replacement <b><?php echo ucfirst( strtolower( $part_type ) ); ?><?php echo ucfirst( strtolower($sub_keywords)); ?></b> to fit a range of vehicle manufactures, including Ford, BMW, Audi Volkswagen.</p>
              </div>
<div class="row">
<?php 
		      if( $part_type == 'bumpers' ):
		?>
		<div class="btn-group pull-right" role="group" aria-label="<?php echo ucfirst( strtolower( $part_type ) ); ?>" style="margin-bottom:5px;">
		 
		<a href="/front-bumpers" title="Replacement Front Bumpers" class="btn btn-default" >Front Bumpers</a>
		
		<a href="/rear-bumpers" title="Replacement Rear Bumpers" class="btn btn-default" >Rear Bumpers</a>
		
		<a href="/bumper-end-caps" title="Replacement Bumper End Caps" class="btn btn-default" >Bumper End Caps</a>
		
		<a href="/bumper-spoilers" title="Replacement Bumper Spoilers" class="btn btn-default" >Bumper Spoilers</a>
		</div>

		<?php elseif( $part_type == 'wings' ): ?>
		<div class="btn-group pull-right" role="group" aria-label="<?php echo ucfirst( strtolower( $part_type ) ); ?>" style="margin-bottom:5px;">
		
				<a href="/front-wings" title="Replacement Front Wings" class="btn btn-default" >Front Wings</a>
		
				<a href="/inner-wings" title="Replacement Inner Wings" class="btn btn-default" >Inner Wings</a>
		
				<a href="/rear-wings" title="Replacement Rear Wings" class="btn btn-default" >Rear Wings</a>
		</div>

		<?php elseif( $part_type == 'lighting' ): ?>
		
		<div class="btn-group pull-right" role="group" aria-label="<?php echo ucfirst( strtolower( $part_type ) ); ?>" style="margin-bottom:5px;">
		
		<a href="/headlamps" title="Replacement Headlamps" class="btn btn-default" >Headlamps</a>
		
		<a href="/rear-lamps" title="Replacement Rear Lamps" class="btn btn-default" >Rear Lamps</a>
		
		<a href="/fog-and-spot-lamps" title="Replacement Fog &amp; Spot Lamps" class="btn btn-default" >Fog &amp; Spot Lamps</a>
		
		<a href="/indicators-and-daylight-running" title="Replacement Fog &amp; Spot Lamps" class="btn btn-default" >Indicators &amp; Daylight Running</a>
		
		<a href="/side-repeaters" title="Replacement Side Repeaters" class="btn btn-default" >Side Repeaters</a>
		</div>

		<?php elseif( $part_type == 'cooling' ): ?>
       <div class="btn-group pull-right" role="group" aria-label="<?php echo ucfirst( strtolower( $part_type ) ); ?>" style="margin-bottom:5px;">
		
		<a href="/radiators-and-condensors" title="Replacement Radiators and Condensors" class="btn btn-default" >Radiators &amp; Condensors</a>
		
		<a href="/radiator-fans" title="Replacement Radiator Fans" class="btn btn-default" >Radiator Fans</a>
		</div>
		
		<?php elseif( $part_type == 'mouldings-and-trims' ): ?>
        <<div class="btn-group pull-right" role="group" aria-label="<?php echo ucfirst( strtolower( $part_type ) ); ?>" style="margin-bottom:5px;">
		
		<a href="/mouldings-and-wing-extensions" title="Replacement Wing Extensions" class="btn btn-default" >Mouldings &amp; Wing Extensions</a>
		
		<a href="/spoilers" title="Replacement Spoilers" class="btn btn-default" >Spoilers</a>
		
		<a href="/tow-hook-covers" title="Replacement Tow Hook Covers" class="btn btn-default" >Tow Hook Covers</a>
		</div>
 
		
		<?php elseif( $part_type == 'tailgates-and-doors' ): ?>
		<div class="btn-group pull-right" role="group" aria-label="<?php echo ucfirst( strtolower( $part_type ) ); ?>" style="margin-bottom:5px;">
		
		<a href="/doors" title="Replacement Car Doors" class="btn btn-default" >Doors</a>
		
		<a href="/tailgates" title="Replacement Tailgates" class="btn btn-default" >Tailgates</a>
		
		<a href="/boot-lids" title="Replacement boot lids" class="btn btn-default" >Boot Lids</a>
		</div>

<?php else: ?>

		<?php endif; ?>
</div>

	<div clas="row">
		<?php if( !empty( $parts ) && is_array( $parts ) ): ?>
		
		
		<?php foreach( $parts as $line ): ?>
		
			<div class="col-xs-12 thumbnail" >
		
		<div class="col-xs-12 col-md-2"><?php if( $line[ 'product_image' ] == 'No' ): ?>
		<img src="http://www.newvehicleparts.co.uk/assets/nvp/images/no_image.jpg" class="img img-responsive center-block" alt="<?php echo $line[ 'manufacture' ];  ?> <?php echo $line[ 'model' ];  ?> <?php if(!empty($line[ 'group_sub_desc' ])): ?><?php echo rtrim($line['group_sub_desc'], 's');  ?><?php else: ?><?php echo rtrim($line['group_desc'], 'S');  ?><?php endif; ?>" />
		
		<?php else: ?>
		<img src="http://www.qparts.co.uk/images/<?php echo $line[ 'sku' ]; ?>.jpg" class="img img-responsive center-block" alt="<?php echo $line[ 'manufacture' ];  ?> <?php echo $line[ 'model' ];  ?> <?php if(!empty($line[ 'group_sub_desc' ])): ?><?php echo rtrim($line['group_sub_desc'], 's');  ?><?php else: ?><?php echo rtrim($line['group_desc'], 'S');  ?><?php endif; ?>" />	
		
		<?php endif; ?>
		</div>
		<div class="col-xs-12 col-md-6"><h2 style="font-size: 20px"><?php echo ucfirst(strtolower($line[ 'manufacture' ]));  ?> <?php echo ucfirst(strtolower($line[ 'model' ]));  ?> <?php echo rtrim($line['group_sub_desc'], 's');  ?> </h2>
		<p>This Replacement 
		<b>
		<?php if(!empty($line[ 'group_sub_desc' ])): ?>
		<?php echo rtrim($line['group_sub_desc'], 's');  ?> 
		<?php else: ?>
		<?php echo rtrim($line['group_desc'], 'S');  ?> 
		<?php endif; ?>
		</b> will fit<b> <?php echo ucfirst(strtolower($line[ 'manufacture' ]));  ?> <?php echo ucfirst(strtolower($line[ 'model' ]));  ?></b><br />
		Year Range: <?php echo $line[ 'start_year' ];  ?> - <?php echo $line[ 'end_year' ];  ?><br />Product info:<b> <?php echo $line['product'];  ?></b></p>
		</div>
		<div class="col-xs-12 col-md-4"><?php $manufacture_url = strtolower( misc::urlencode( $line[ 'manufacture' ] ) ); $model_url = strtolower( misc::urlencode( $line[ 'model' ] ) );
		$href = BASE_URL.$manufacture_url.'/'.$model_url.'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) );
		$data_smap_node = $line [ 'product' ];
		?>




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