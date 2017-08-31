<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

<?php if( !empty( $meta_title ) ): ?>
<title><?php echo $meta_title; ?></title>
<?php endif; ?>
<link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png">
<!--[if IE]><link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico"><![endif]-->
<?php if( !empty( $meta_description ) ): ?>
<meta name="description" content="<?php echo misc::esc_attr( $meta_description ); ?>"/>
<?php endif; ?>

<?php if( !empty( $meta_keywords ) ): ?>
<meta name="keywords" content="<?php echo misc::esc_attr( $meta_keywords ); ?>"/>
<?php endif; ?>

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
<meta name="msvalidate.01" content="96251DBA587115E4E32C927D8D9FC98F" />
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
		<form>
		<div id="simple-search-input" class="input-group">
	<input id="simple-search" type="text" class="form-control" placeholder="Search for Car Parts" />
	
</div>
		</form>

	</div>

</div>
<div id="content-wrapper">
	<div>