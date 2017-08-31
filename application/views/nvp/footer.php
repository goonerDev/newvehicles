	
	
	</div>
	<img id="payment-logo" src="<?php echo BASE_URL; ?>assets/<?php echo $view_dir; ?>/images/Case-Of-Six-Logo.png" alt="Replacement Car parts UK Crash Repair Parts, Car Panels, Car Lamps" class="pull-right img image-responsive" style="margin-bottom:-1px;max-width:375px;" />
	
</div>
<div id="outmost-footer-wrapper" class="clear">
	<div id="footer" class="clearfix">
	<div class="row">
<div class="col-xs-12">
<p style="color:#fff; font-size:130%">Most Popular categories</p>
</div>
<div class="col-xs-12" style="color:#fff; ">
<a href="http://www.newvehicleparts.co.uk/door-mirrors" style="color:#fff; " title="Replacement Car Door Mirrors"><b>Door Mirrors</b></a> | 
<a href="http://www.newvehicleparts.co.uk/grilles" style="color:#fff; " title="Replacement Car Parts UK - Grilles"><b>Grilles</b></a> | 
<a href="http://www.newvehicleparts.co.uk/bonnets" style="color:#fff; " title="Replacement Auto Parts UK - Bonnets"><b>Bonnets</b></a> | 
<a href="http://www.newvehicleparts.co.uk/headlamps" style="color:#fff; " title="Asta Head Lighes"><b>Head Lights</b></a> | 
<a href="http://www.newvehicleparts.co.uk/rear-lamps" style="color:#fff;" title="Corsa Rear Lamps - Uk Car Parts"><b>Rear Lamps</b></a> | 
<a href="http://www.newvehicleparts.co.uk/fog-spot-lamps" style="color:#fff; " title="Replacement Fog lamps - Online UK"><b>Fog &amp; Spot Lamps</b></a> | 
<a href="http://www.newvehicleparts.co.uk/indicators-and-daylight-running" style="color:#fff; " title="Replacement Car Indicators"><b>Indicators</b></a> | 
<a href="http://www.newvehicleparts.co.uk/bumpers" style="color:#fff; " title="Replacement Bumpers for Ford Focus - astra vxr front bumper UK"><b>Bumpers</b></a> |
<a href="http://www.newvehicleparts.co.uk/front-bumpers" style="color:#fff; " title="Replacement Ford Focus front Bumper"><b>Front Bumpers</b></a> | 
<a href="http://www.newvehicleparts.co.uk/rear-bumpers" style="color:#fff; " title="Audi A3 Bumpers"><b>Rear Bumpers</b></a> | 
<a href="http://www.newvehicleparts.co.uk/wings" style="color:#fff; " title="UK Car Parts Online Wings"><b>Wings</b></a> |  

</div>
</div>
		<ul class="horiz-list">
			<li class="header">Contact Info
				<ul>
					<li><span class="glyphicon glyphicon-envelope"></span> newvehicleparts@gmail.com</li>
					<li><span class="glyphicon glyphicon-earphone"></span> +44 (0) 1405 810 876</li>
					<li><span class="glyphicon glyphicon-map-marker"></span> Unit 4 Alpha Court</li>
					<li><span class="glyphicon glyphicon-minus transparent"></span> Capitol Park,</li>
					<li><span class="glyphicon glyphicon-minus transparent"></span> Thorne,</li>
					<li><span class="glyphicon glyphicon-minus transparent"></span> DN8 5TZ</li>
				</ul>
			</li>
			<li class="header">Help
				<ul>
					<!--<li><a href="<?php echo BASE_URL; ?>about-us" class="smap-node" data-smap-node="About us" data-smap-follow="0" data-smap-level="0">About</a></li>-->
					<li><a href="<?php echo BASE_URL; ?>delivery-charges" class="smap-node" data-smap-node="Delivery Charges" data-smap-follow="0" data-smap-level="0">Deliveries Charges</a></li>
					<li><a href="<?php echo BASE_URL; ?>reviews" class="smap-node" data-smap-node="Reviews" data-smap-follow="0" data-smap-level="0">Reviews</a></li>
					<li><a href="<?php echo BASE_URL; ?>terms-conditions" class="smap-node" data-smap-node="Terms and conditions" data-smap-follow="0" data-smap-level="0">Terms &amp; Conditions</a></li>
					<li><a href="<?php echo BASE_URL; ?>sitemap/">Site Map</a></li>
				</ul>
			</li>
			<li class="header">Connect with Us
			
			<ul>
			<li><a href="https://www.facebook.com/NewVehicleParts" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a></li>
			<li> <a href="https://plus.google.com/+newvehiclepartscoukThorne/about" target="_blank" rel="publisher"><i class="fa fa-google-plus-square fa-2x"></i></a></li> 
			 <li> <a href="https://twitter.com/Newvehicleparts" target="_blank"><i class="fa fa-twitter-square fa-2x"></i></a></li>	
			</ul>
			</li>
			
		</ul>
	
	</div>
	<p style="color:#fff;margin-left:30%">Copyright &copy; New Vehicle Parts Online 2014 - <?php echo date ('Y'); ?> | Registered in England and Wales No.  6972549 | VAT No. 104 9292 93</p>
</div> 
<div class="pull-right">
<?php $richSnippets = file_get_contents("http://dash.reviews.co.uk/external/rich-snippet/new-vehicle-parts");
if (strpos($http_response_header[0],"200") > 0) {
echo $richSnippets;
} ?>
</div>
<?php if( !empty( $scripts ) ): foreach( $scripts as $script ): ?>
<script src="<?php echo $script; ?>"></script>
<?php endforeach; endif; ?>

<?php if( !empty( $inline_scripts ) ): foreach( $inline_scripts as $script ):
echo $script;
endforeach; endif; ?>

</body>
</html>