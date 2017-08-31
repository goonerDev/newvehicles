<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<?php
$product_title = preg_split( '/\s+/', $item[ 'product' ] );
$product_title = @ucwords( strtolower( @$product_title[ 0 ].' '.@$product_title[ 1 ].' '.@$product_title[ 2 ] ) );
?>
<title> <?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?> <?php echo ucwords(strtolower($item[ 'product' ])); ?> </title>
<link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png">

<!--[if IE]><link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico"><![endif]-->

<meta name="keywords" content="<?php echo $product_title; ?>, <?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?>" />

<meta name="description" content="This <?php echo $product_title; ?> is Brand New and designed to fit a <?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?><?php if ( $item[ 'end_year' ] == "-" ): ?> <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> onwards,<?php else: ?> from <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> to <?php echo ucwords(strtolower($item[ 'end_year' ])); ?>,<?php endif; ?> all our aftermarket parts are insurance apporved parts"/>


<?php /* Specific css style, only seen in some pages*/ ?>
<?php if( !empty( $links ) ): foreach( $links as $link ): ?>
<link rel="stylesheet" href="<?php echo $link; ?>"/>
<?php endforeach; endif; ?>
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon.png" rel="apple-touch-icon" />
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
<link href="<?php echo BASE_URL; ?>assets/images/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />

<script>var host='<?php echo BASE_URL; ?>'</script>
<script>var cur_page='<?php echo @$cur_page; ?>'</script>
 <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
@media (max-width: 767px){ .hidden-sm{display:none;}}
</style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43976078-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>

<?php require_once 'loading-animation-dialog.php'; ?>

<div id="outmost-top-wrapper">
	<div id="topmost" class="clearfix">
		<?php require_once 'menu.php'; ?>
	</div>
</div>

<div id="header-wrapper" class="clearfix">

	<div id="header-logo">
		<a href="<?php echo BASE_URL; ?>" title="with over 1800+ parts in stock, we stock bonnets, bumpers, lights, mirrors, pannels &amp; cooling brand new and at the lowest prices on the net"><img src="<?php echo BASE_URL; ?>assets/<?php echo $view_dir; ?>/images/nvpo-logo.png"  alt="Crash Repair Parts - New Vehicle Parts Online" class="img-responsive"></a>
	</div>

	<div id="header-contact-section">

		<p><span class="glyphicon glyphicon-earphone fs-14 hidden-xs"></span> <span class="fs-14 hidden-sm">+44 (0) 1405 810 876</span></p>

		<p><span class="glyphicon glyphicon-envelope fs-14 hidden-xs"></span> <span class="fs-14 hidden-sm">sales@newvehicleparts.co.uk</span></p>

	</div>

</div>
<div id="content-wrapper">
	<div>

	<div class="row">
	
		<div class="col-xs-12 col-md-12">
		<?php // Banner ?>
		<img src="http://www.newvehicleparts.co.uk/assets/images/banner_ad.png" style="margin-bottom:8px;" class="hidden-xs" alt="<?php echo $item[ 'manufacture' ]; ?> <?php echo $item[ 'model' ]; ?> <?php echo $item[ 'product' ]; ?>" />
		</div>
		
	</div>
	<?php /* Breadcrumb */ ?>
		<div class="row">

        <div class="btn-group btn-breadcrumb">
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
            <a href="<?php echo BASE_URL; ?>make" class="btn btn-primary hidden-xs">All Manufacturers</a>
            <a href="<?php echo BASE_URL.strtolower( misc::urlencode( $item[ 'manufacture' ] ) ) ; ?>" class="btn btn-primary hidden-xs"><?php echo $item[ 'manufacture' ]; ?></a>
	    <a href="<?php echo BASE_URL.strtolower( misc::urlencode( $item[ 'manufacture' ] ) ); ?>/<?php echo strtolower( misc::urlencode( $item[ 'model' ] ) ); ?>" class="btn btn-primary hidden-xs"><?php echo $item[ 'model' ]; ?></a>
	    <a href="<?php echo BASE_URL.strtolower( misc::urlencode( $item[ 'manufacture' ] ) ); ?>/<?php echo strtolower( misc::urlencode( $item[ 'model' ] ) ); ?>/<?php echo strtolower( misc::urlencode( $item[ 'model_type' ] ) ); ?>" class="btn btn-primary"><?php echo $item[ 'model_type' ]; ?></a>
	    <a href=#" class="btn btn-info"><?php echo ucwords(strtolower($item[ 'product' ])); ?></a>

        </div>
		
	</div><!-- End Row -->
	<!--
	<div class="row">
<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4>Order Tacking!</h4>
  <p>With on going service improvement we're happy to annonuce we've just lanuched our tracking service, so you'll have peace of mind when ordering from New Vehicle Parts!</p>
  <p>On all orders placed on our site, you'll recieve an order confirmation on placement of your order, then you'll receieve a second email once the item(s) have been marked as dispatched with estimated delivery time and an order tracking code.</p>
  <p>Thanks<br /> New Vehicle Parts</p>
</div>
</div> -->
<div class="row hidden-xs">
		<div class="col-xs-12 col-sm-12 col-lg-12">
		<h1 style="font-size:20px;"><b><?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?> <?php echo $product_title; ?></b> <?php if ( $item[ 'end_year' ] == "-" ): ?> <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> onwards<?php else: ?> <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> to <?php echo ucwords(strtolower($item[ 'end_year' ])); ?><?php endif; ?></h1>
		</div>



</div>	<!-- End Row -->
	<div itemscope itemtype="http://schema.org/Product">	
	<div id="product-detail-image" class="row">
	
		<div class="col-md-8">
			<?php if( $item[ 'product_image' ] == 'No' ): ?>
			
				<img src="http://www.newvehicleparts.co.uk/assets/nvp/images/no_image.jpg" alt="<?php echo $item[ 'product' ]; ?> for <?php echo $item[ 'manufacture' ]; ?> <?php echo $item[ 'model' ]; ?> " class="img img-responsive" style="margin:auto;width:70%;padding-bottom:35px;"/>
			
				<?php else: ?>

				<img src="http://www.qparts.co.uk/images/<?php echo $item[ 'sku' ]; ?>.jpg" alt="<?php echo $item[ 'product' ]; ?> for <?php echo $item[ 'manufacture' ]; ?> <?php echo $item[ 'model' ]; ?>" class="img-responsive zoomable" rel="zoom" style="margin:auto;width:70%;padding-bottom:35px;"/>
<span class="pull-right hidden-xs">[ <i class="fa fa-search-plus"></i> Click image to enlarge ]</span>
			

			
			<?php endif; ?>
			   
			<h2 style="font-weight:bold;font-size:16px;color: #009cff;"><span itemprop="name"><?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?> <?php echo $item[ 'product' ]; ?> <?php if ( $item[ 'end_year' ] == ' ' ): ?> <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> onwards<?php else: ?> from <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> - <?php echo ucwords(strtolower($item[ 'end_year' ])); ?><?php endif; ?></span></h2>
			<p class="hidden-xs">
		
		<?php $group_desc = strtolower( trim( $item[ 'group_desc' ] ) );
		      if( $group_desc == 'consumables' ):
		?>


		<?php elseif( $group_desc == 'misc' ): ?>
		
		<?php elseif( $group_desc == 'repair pannels' ): ?>

<?php else: ?>
<span itemprop="description"><b><?php echo $product_title; ?> </b>for <b><?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?> </b> <?php if ( $item[ 'end_year' ] == "-" ): ?> fits <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> onwards<?php else: ?> for <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> to <?php echo ucwords(strtolower($item[ 'end_year' ])); ?><?php endif; ?> 

<!--This <strong><?php echo $item[ 'product' ]; ?></strong> is <strong>New</strong> and is designed to fit <strong><?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?> <?php if ( $item[ 'end_year' ] == "-" ): ?> <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> onwards<?php else: ?> <?php echo ucwords(strtolower($item[ 'start_year' ])); ?> to <?php echo ucwords(strtolower($item[ 'end_year' ])); ?><?php endif; ?></strong> -->
<!-- We can assure you that this <strong> <?php echo $product_title; ?></strong> is the best you can find on the web for aftermarket parts,--> 
<!--we deal direct from high quality--> 
<!--aftermarket -->
<!--manufacturers which have stringent Quality Testing thought out the manufacturing process of this<strong> <?php echo $product_title; ?></strong>.<br /><br />  So you can shop with confidence that you are buying the highest quality parts at competitive prices, all our <strong><?php echo $product_title; ?></strong> are  <strong>Insurance approved crash repair parts</strong>, This <?php echo $product_title; ?>  has the following <strong><?php echo $item[ 'certificate' ]; ?></strong> certification.--></span>
		<?php endif; ?>
		

		</p>
		<p></p>
		<?php $group_desc = strtolower( trim( $item[ 'group_desc' ] ) );
		      if( $group_desc == 'consumables' ):
		?>


		<?php elseif( $group_desc == 'misc' ): ?>
		
		<?php elseif( $group_desc == 'repair pannels' ): ?>
<?php else: ?>
<h2 style="font-weight:bold;font-size:16px;color: #009cff;">Product Specification Info</h2>
				
				<div class="col-xs-12 col-md-6" style="background-color:#e2e4e1;padding:3px;border:1px solid #fff;">
					Product Code: <strong><span itemprop="sku"><?php echo $item[ 'sku' ]; ?></span></strong>
				</div>
			
				<div class="col-xs-12 col-md-6" style="background-color:#e2e4e1;padding:3px;border:1px solid #fff;">
					Manufacturer: <strong><span itemprop="manufacturer"><?php echo ucwords(strtolower($item[ 'manufacture' ])); ?></span></strong>
				</div>
			
				<div class="col-xs-12 col-md-6" style="padding:3px;border:1px solid #fff;"> 
					Model: <strong><span itemprop="model"><?php echo ucwords(strtolower($item[ 'model' ])); ?></span></strong>
				</div>
			
				<div class="col-xs-12 col-md-6" style="padding:3px;border:1px solid #fff;">
					Start - End year: <strong><?php echo $item[ 'start_year' ].' - '.( $item[ 'end_year' ] == 0 ? '' : $item[ 'end_year' ] ); ?></strong>
				</div>
			
				<div class="col-xs-12 col-md-6" style="background-color:#e2e4e1;padding:3px;border:1px solid #fff;">
				Model type: <strong><span itemprop="model"><?php echo ucwords(strtolower($item[ 'model_type' ])); ?> </span></strong>
				</div>
			
				<div class="col-xs-12 col-md-6" style="background-color:#e2e4e1;padding:3px;margin-bottom:10px;border:1px solid #fff;">
				<?php if( $item[ 'certificate' ] == '' ): ?>
				
					<?php else: ?>
				
					Certificate: <strong><?php echo $item[ 'certificate' ]; ?></strong>
				
					<?php endif; ?>
				</div>
		<?php endif; ?>
		<div class="row"></div>
		 <?php if( is_array( $vehic_fit ) && count( $vehic_fit ) > 1 ): ?>
 
       
       

        <h2 style="font-weight:bold;font-size:16px;color: #009cff;">This <?php echo $item[ 'product' ]; ?> will fit the following vehicles.</h2>
<div class="row" style="padding:11px;">


	<?php foreach( $vehic_fit as $vehic ): ?>
	
		<div class="col-xs-12 col-md-6" style="background-color:#e2e4e1;padding:3px;border:1px solid #fff;">
			<a href="<?php echo BASE_URL.strtolower( misc::urlencode( $vehic[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $vehic[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $vehic[ 'model_type' ] ) ); ?>">
				<?php echo ucwords(strtolower($vehic[ 'model_type' ])); ?>
			</a>
		</div>
	<?php endforeach; ?>

</div>
   
       <?php endif; ?>
 
       
       <h4>If colour matching is required on this Item</h4>
<p><b>Please Note:<br /></b>Our parts only come in either primed or non primed parts, unless otherwise stated and whilst ever effort is made to ensure accuracy of specifications of this Item, <b>before you get the item sprayed to the colour you require, please make sure it fits correctly to your vehicle</b>, as we are unable to offer an exchange or refund on parts that don't fit correctly after they have been colour matched.</p>
      <!-- <h2 style="font-weight:bold;font-size:16px;color: #009cff;" class="hidden-xs">Our Expertise</h2>
        <p class="hidden-xs">We are one of UK's leading supplier of <strong>Aftermarket Crash Repair Parts</strong>, Refinishing Products, Sheet &amp; Box Metals suppliers, with thousands parts in stock you should find what you need all on our site to get the job done. We buy all our <strong><?php echo ucwords(strtolower($item[ 'manufacture' ])); ?> <?php echo ucwords(strtolower($item[ 'model' ])); ?></strong> direct from leading manufacture's. No need to shop around, we've done all the hard work for you we track our competitors prices and keep our prices as competitive as possible,so you can shop with confidence that you are buying the highest quality <strong><?php echo $item[ 'product' ]; ?></strong> at the lowest prices guaranteed!</p>
	 <p class="hidden-xs"><b>We do everything to ensure your order arrives at your door complete and in pristine condition, we are unparalleled for non-damaged first time delivery.</b></p>-->
		
	</div>
	
<div class="col-xs-12 col-lg-4">
<div class="col-xs-12" style="padding:5px;border:1px solid #999;background:#f0efef;margin-top:5px;">
		<form name="add-cart-form" method="post" action="<?php echo BASE_URL; ?>cart/update_qty_by_sku/<?php echo $item[ 'sku' ]; ?>" >
	<h4 style="text-align:center;font-size:160%" ><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</h4>
<?php if( !empty( $price ) ): $price = misc::get_available_prices( $price, $user_no, $vat, 0 ); endif; ?>
		
			<?php if( ( int ) $item[ 'stock' ] == 0 || empty( $price ) || $price[ 'price' ] == E_CALL_FOR_PRICE ): ?>
		
				<span class="label label-danger" style="padding:10px;margin:10px;"><span class="glyphicon glyphicon-remove"></span> Out of Stock</span><br/><br/><br/>
			
				<strong>Price:  </strong> &pound; <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<span itemprop="price"><?php echo @$price[ 'price' ]; ?></span>
				<meta itemprop="availability" content="http://schema.org/OutOfStock"/>
				<meta itempropCurrency" content="GBP" /> <br /><small>All Prices are <b>VAT</b> inclusive.</small>

					</span>
				
			<?php else: ?>
		<div class="col-xs-12"></div>
				

		                <div class="col-xs-12" style="margin-top:10px;">
					<strong>Quantity:</strong> <input type="text" name="qty" value="<?php echo empty( $item_in_cart[ 'qty' ] ) ? 1 : $item_in_cart[ 'qty' ]; ?>" class="numeric-align" size="1"/> <span class="label label-success" style="padding:10px;margin:10px;"><span class="glyphicon glyphicon-ok"></span>In Stock</span>
				
				</div>
				<div class="col-xs-12" style="margin-top:10px;">
					<strong>Price: </strong> &pound; <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<span itemprop="price"><?php echo @$price[ 'price' ]; ?></span>
					<meta itemprop="availability" content="http://schema.org/InStock"/>
					<meta itempropCurrency" content="GBP" /> <br /><small>All Prices are <b>VAT</b> inclusive.</small>
					
					</span>
					
		</div>
				<div class="col-xs-12" style="margin-top:10px;">
					<button type="submit" class="btn btn btn-success<?php echo ( $price == 0 ? ' disabled' : '' ); ?>" style="width:95%">
						<span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
					</button>
					<!--<br />
					<br />The cost for UK delivery for this item is: <b>&pound; <?php echo ucwords(strtolower($item[ 'ebay_carriage' ])); ?></b>-->
					
				</div>
		
				
		
				<?php endif; ?>
		
			<?php //endif; ?>

			</form>
		</div>	
</div>
			
			<div class="col-xs-12 col-lg-4" >
			<?php if( !empty( $price ) ): $price = misc::get_available_prices( $price, $user_no, $vat, 0 ); endif; ?>
			<?php if( ( int ) $item[ 'stock' ] == 0 || empty( $price ) || $price[ 'price' ] == E_CALL_FOR_PRICE ): ?>
			
			<?php else: ?>
			<div class="lable label-primary" style="padding:5px;color:#fff;margin-top:20px;"><h4 style="text-align:center;font-size:160%"><img src="http://www.newvehicleparts.co.uk/assets/nvp/images/delivery_icon.png"> Delivery</h4>
			
			
			<?php //Christmas Period
				date_default_timezone_set( 'Europe/London' );

				$time = time();
				$week_day = date( 'w', $time );
				$year = date( 'Y', $time );
				$month = date( 'm', $time );
				$day = date( 'd', $time );
				$hour = date( 'H', $time );

				if( 1 <= $week_day && $week_day <= 4 && $hour < 17 ) {
					$order_time = '5pm';
					$expected_delivery_date = date( 'D dS M Y', $time + 86400 );
				}
				elseif( 1 <= $week_day && $week_day <= 3 && 17 <= $hour ) {
					$order_time = 'midnight';
					$expected_delivery_date = date( 'D dS M Y', $time + 172800 );
				}
				elseif( $week_day == 4 && 17 <= $hour ) {
					$order_time = 'midnight';
					$expected_delivery_date = date( 'D dS M Y', $time + 345600 );
				}
				elseif( $week_day == 5 && $hour < 17 ) {
					$order_time = '5pm';
					$expected_delivery_date = date( 'D dS M Y', $time + 259200 );
				}
				elseif( $week_day == 5 && 17 <= $hour ) {
					$order_time = 'midnight';
					$expected_delivery_date = date( 'D dS M Y', $time + 259200 ); //+ 345600
				}
				elseif( $week_day == 6 ) {
					$order_time = 'Monday';
					$expected_delivery_date = date( 'D dS M Y', $time + 259200 );
				}
				elseif( $week_day == 0 ) {
					$order_time = 'Monday';
					$expected_delivery_date = date( 'D dS M Y', $time + 172800 );
				}
			?>
			<p style="padding:5px;">
			
			<b>Estimated Delivery:</b> <?php echo $expected_delivery_date; ?><br /> 
			<br />
			<b>Returns:</b> 30 days.  <small > <a href="/terms-conditions#returns" style="color:#fff;"> | see details</a></small><br />
			<br />
			<b>Standard Carriage:</b> &pound;<?php echo $item[ 'ebay_carriage' ]; ?> <small ><a href="/delivery-charges" style="color:#fff;"> | see details</a></small><br />
			<br /><b>Item(s) Tracking:</b><small>Once marked as dispatched, a tracking code will be sent via email.</small><br />
			
			</p></div>
			<?php endif; ?>
			
			
		<script src="//dash.reviews.co.uk/widget/badge_popup.js"></script><div data-badge="18356"> <iframe id="custom-2" frameborder="0" scrolling="no" style="border:0px;height:140px;width:100%;margin-top:10px;overflow:none;" src="https://secure.reviews.co.uk/badge/mini/18356?colour1=79D236&colour2=4BA609"></iframe></div> 
			
			<div class="lable label-danger" style="padding:5px;color:#fff;margin-top:20px;">
       <h4>Parts Guide</h4>
       <p>All our Items are <meta itemprop="itemCondition" itemtype="http://schema.org/OfferItemCondition" content="http://schema.org/NewCondition"/>New</p>
       
			<p style="font-weight:normal;font-size:12px;">Make sure you order the correct part(S)</p>
				<p class="small" ><strong>L/H</strong> = Near side (Passenger Side)</p>
				<p class="small" ><strong>R/H</strong> = Offside (Drivers Side)</p>	
				<p></p>
                               <h4>Colour Matching</h4>
<p>We Don't offer colour matched parts, only primed and non-primed parts, unless otherwise stated</div> 
			
			
		
	   
	</div><!-- End Row -->
        <div class="row hidden-xs"> 
        
  </div>
</div>	
</div>

<?php require_once 'footer.php';