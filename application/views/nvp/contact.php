<?php require_once 'header.php'; ?>

<div class="content-section">
	<div class="col-sm-6 col-xs-12">
		<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500 color-171f6a">Contact us</h1>

		<form action="<?php echo base_url(); ?>contact/process" method="post">
			<div class="form-group col-xs-12">
				<input type="text" class="form-control" id="fname" name="fname" value="" placeholder="First name"/>
			</div>
			<div class="form-group col-xs-12">
				<input type="text" class="form-control" id="lname" name="lname" value="" placeholder="Last name"/>
			</div>
			<div class="form-group col-xs-12">
				<input type="text" class="form-control" id="email_c" name="email_c" value="" placeholder="Email"/>
			</div>
			<div class="form-group col-xs-12">
				<input type="text" class="form-control" id="company" name="company" value="" placeholder="Company"/>
			</div>
			<div class="form-group col-xs-12">
				<textarea class="form-control" id="message" name="message" rows="10" placeholder="Message"></textarea>
			</div>
			<p align="center">
				<input type="submit" id="contact-send-msg-btn" class="btn btn-success" value="Send"/>
			</p>
		</form>
	</div>
	<div class="col-sm-6 col-xs-12">
		<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500 color-171f6a">Contact details</h1>
		Unit 4 Alpha Court,<br/>
		Capitol Park,<br/>
		Thorne,<br/>
		DN8 5TZ<br /><br />
		
		<span class="glyphicon glyphicon-earphone"></span> +44 (0) 1405 810 876<br />
		<span class="glyphicon glyphicon-envelope"></span> newvehicleparts@gmail.com<br />
		</div>
		
		
	</div>
</div>
<?php require_once 'footer.php';