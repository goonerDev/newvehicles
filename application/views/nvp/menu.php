<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
	<?php echo $cart; ?>
		<div class="navbar-header" id="collapsible-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapsible" id="collapsible-toggler">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="collapsible">
			<ul class="nav navbar-nav">
				<li class="<?php echo ( !empty( $home_active ) ? 'active' : '' ); ?>"><a href="<?php echo BASE_URL; ?>" title="Online Replacemnt Car Parts Suppliers UK" >Home</a></li>
				<li class="<?php echo ( !empty( $catalog_active ) ? 'active' : '' ); ?>"><a href="<?php echo BASE_URL; ?>make" title="UK Replacement Car Parts Online ">Replacement Car Parts</a></li>
				<li><a href="<?php echo BASE_URL; ?>contact" title="Looking for a New Bonnet, Front or Rear Bumpers, Indications,Front or Rear Lights for your Car or Van any make or model we have them in stock">Contact us</a></li>
				<li><a href="<?php echo BASE_URL; ?>checkout" title="checkout page">Checkout</a></li>
		</ul>
		</div>
	</div>
</div>