<?php require_once 'header.php'; ?>
<img src="http://www.newvehicleparts.co.uk/assets/nvp/images/GlobalSign-Trust-Seal.png" style="width:150px"  class="pull-right"/>
<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">

	<span class="color-171f6a">Checkout</span>
</h1>
<p style="margin-bottom:10px;">You are now in our Secure one page Checkout, <b>all orders placed before 5pm we aim to deliver next working day to UK Mainland addresses,</b> For Scottish Highlands, Isle of Wight, Isle of Man, Channel Islands, Northern & Southern Ireland and Isles of Scilly a fixed shipping charge of £30.00 applies.<br />
<br /><strong>Unfortunatly any orders placed to countries other than United Kingdom, Isle of Man, Channel  Islands, Ireland &amp; Isle of Scilly can not be processed, if in doubt call +44 (0) 1405 810 876</strong></p>

<p>Having trouble checking out?<a href="mailto:prascosale@gmail.com"> Let us know</a></p>
<div id="cart-checkout-form">

	<div class="col-xs-12 clearfix">
		<div class="col-xs-12 no-padding-right">
		<h2>Your order details</h2>
			<?php if( empty( $items ) ): ?>
			<p class="alert alert-danger"><?php echo E_EMPTY_CART; ?></p>
			<?php else: ?>
			<table id="items" class="table table-striped">
				<thead>
					<tr>
						<th width="65%">Product</th>
						<th class="numeric-align">P<span class="hidden-xs">rice</th>
						<th >Q<span class="hidden-xs">uantity</span></th>
						<th width="20%" class="numeric-align">T<span class="hidden-xs">otal</th>
						<th width="8%"></th>
					</tr>
				</thead>
				<tbody>
		<?php
		$total = 0;
		foreach( $items as $item ):
			if( strtolower( $item[ 'sku' ] == 'zcarriage' ) )
				continue;

			$sub_total = number_format( $item[ 'qty' ] * $item[ 'pu' ], 2, '.', ' ' );
		?>
					<tr>
						<!-- Product name -->
						<td>
					<?php echo ( $item[ 'sku' ] == 'zcarriage' ? 'Carriage' : '<strong>'.$item[ 'product' ].'</strong> - '.$item[ 'model_type' ] ); ?>
						</td>
						<!-- Unit price -->
						<td><div id="pu-<?php echo $item[ 'id' ]; ?>" class="numeric-align"><?php echo number_format( $item[ 'pu' ], 2 ); ?></div></td>
						<!-- Qty input -->
						<td><input id="input-<?php echo $item[ 'id' ]; ?>" type="text" name="qty[<?php echo $item[ 'sku' ]; ?>]" value="<?php echo misc::esc_attr( $item[ 'qty' ] ); ?>" size="1" class="centered"/></td>
						<!-- Total -->
						<td><div id="tt-<?php echo $item[ 'id' ]; ?>" class="numeric-align"><?php echo $sub_total; ?></div></td>
						<td><a href="<?php echo BASE_URL; ?>delete-item/<?php echo $item[ 'id' ]; ?>"><span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>
		<?php endforeach; ?>
					<tr>
						<td id="sub-total-shipping-msg" colspan="3">Shipping</td>
						<td id="sub-total-shipping-rate" class="numeric-align"><?php echo $shipment_rate == 0 ? '<strong><span style="color:#fc0202">postcode required</span></strong>' : number_format( $shipment_rate, 2 ); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr style="background-color:#f9f9f9;border-top:1px solid #ddd;">
					<td></td>
					<td></td>
						<td><strong><!--TOTAL  &pound; --></strong></td>
						<td class="pull-right"><span><strong>TOTAL</strong> &pound; </span><span id="total" class="numeric-align"></span></td>
						<td><small>VAT inc</small></td>
					</tr>
				</tbody>
			</table>
			<?php endif; ?>
		</div>
	</div>

	<!--<?php if( !empty( $paypal_form ) ): ?>
	<?php echo $paypal_form; ?>
	<div class="clearfix" style="border:1px solid #007ac5;padding:5px;margin-top:10px;">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
			<img src="<?php echo BASE_URL; ?>/assets/nvp/images/pp-logo-200px.png" border="0" width="100px" style="margin-top:-25px;margin-left:10px;background-color:#fff" /><h3 style="margin-top:-10px;margin-bottom:0;">Express Checkout</h3>As an alternative to filling in the shipping form below; you can use PayPal Express Checkout.<br />
			<a href="#" id="pp-express-checkout-btn" ><img src="<?php echo BASE_URL; ?>/assets/nvp/images/pp-express-checkout.png" border="0" /></a>
		</div>
	</div>
	<?php endif; ?>-->

	<br class="clearfix"/>
<form name="checkout_form" method="post" action="<?php echo BASE_URL; ?>checkout" target="checkout_target"  class="clearfix" autocomplete="off">
	<div id="alert-msg"><?php echo @$err; ?></div>
	<div class="col-xs-12 clearfix">
		<h2>Your details</h2>
		<div class="col-xs-6">
			<div class="col-xs-12">
				<label for="">First Name <span class="required-field">*</span></label>
				<input type="text" name="firstname" value="<?php echo misc::esc_attr( @$cart_details[ 'firstname' ] ); ?>" class="form-control" data-tab-index="1"/>
			</div>

			<div class="col-xs-12">
				<label for="">Last Name <span class="required-field">*</span></label>
				<input type="text" name="lastname" value="<?php echo misc::esc_attr( @$cart_details[ 'lastname' ] ); ?>" class="form-control" data-tab-index="2"/>
			</div>

			<div class="col-xs-12">
				<label for="">E-mail <span class="required-field">*</span> <span class="tip">valid email adress required</span></label>
				<input type="text" name="email" value="<?php echo misc::esc_attr( @$cart_details[ 'email' ] ); ?>" class="form-control" data-tab-index="3"/>
			</div>

			<div class="col-xs-12">
				<label for="">Telephone <span class="required-field">*</span></label>
				<input type="text" name="telephone" value="<?php echo misc::esc_attr( @$cart_details[ 'telephone' ] ); ?>" class="form-control" data-tab-index="4"/>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="col-xs-12">
				<label for="">Address <span class="required-field">*</span></label>
				<input type="text" name="address" value="<?php echo misc::esc_attr( @$cart_details[ 'address' ] ); ?>" class="form-control" data-tab-index="5"/>
			</div>

			<div class="col-xs-12">
				<label for="">City <span class="required-field">*</span></label>
				<input type="text" name="town" value="<?php echo misc::esc_attr( @$cart_details[ 'town' ] ); ?>" class="form-control" data-tab-index="6"/>
			</div>
			<div class="col-xs-12">
				<label for="">Postcode <span class="required-field">*</span></label>
				<input type="text" name="postcode" value="<?php echo misc::esc_attr( @$cart_details[ 'postcode' ] ); ?>" class="form-control" data-tab-index="7" autocomplete="off" />
			</div>

			<div class="col-xs-12">
				<label for="">Country <span class="required-field">*</span></label>
				<select name="country" class="form-control selectpicker" data-tab-index="8">
					<option value="United Kingdom"<?php echo @$cart_details[ 'county' ] == 'United Kingdom'  ? ' SELECTED' : ''; ?>>United Kingdom</option>
					<option value="Isle of Wight"<?php echo @$cart_details[ 'county' ] == 'Isle of Wight'  ? ' SELECTED' : ''; ?>>Isle of Wight</option>
					<option value="Isle of Man"<?php echo @$cart_details[ 'county' ] == 'Isle of Man'  ? ' SELECTED' : ''; ?>>Isle of Man</option>
					<option value="Channel Islands"<?php echo @$cart_details[ 'county' ] == 'Channel Islands'  ? ' SELECTED' : ''; ?>>Channel Islands</option>
					<option value="Northern Ireland"<?php echo @$cart_details[ 'county' ] == 'Northern Ireland'  ? ' SELECTED' : ''; ?>>Northern Ireland</option>
					<option value="Southern Ireland"<?php echo @$cart_details[ 'county' ] == 'Southern Ireland'  ? ' SELECTED' : ''; ?>>Southern Ireland</option>
					<option value="Isles of Scilly"<?php echo @$cart_details[ 'county' ] == 'Isles of Scilly'  ? ' SELECTED' : ''; ?>>Isles of Scilly</option>
					<option value="Other">Other</option>
				</select>
			</div>
		</div>
	</div>

	<div class="col-xs-12 clearfix">
		<h2>Payment method</h2>
		<input type="radio" id="payment_method_pp" name="payment_method" value="Paypal" <?php echo @$cart_details[ 'payment_method' ] == 'Paypal' ? ' CHECKED' : 'checked'; ?> data-tab-index="10"/> <label for="payment_method_pp" checked>Paypal</label><br />
		<input type="radio" id="payment_method_sp" name="payment_method" value="Sagepay" <?php echo empty( $cart_details[ 'payment_method' ] ) || @$cart_details[ 'payment_method' ] == 'Sagepay' ? ' CHECKED' : ''; ?> data-tab-index="9"/> <label for="payment_method_sp">Credit card / Debit card (SagePay)</label>
		<br/>
		

		<h2>Delivery Notes:</h2>
		<textarea name="comments" class="col-xs-12" rows="3" data-tab-index="11"><?php echo @$cart_details[ 'comments' ]; ?></textarea>
	</div>

	<input type="hidden" name="ajaxCall" value="ajax"/>
	
	<?php if( !empty( $items ) ): ?>
	<div class="pull-left">
		<a id="checkout-continue-shopping-btn" href="javascript:history.go(-1)" class="btn btn-primary">Continue shopping</a>
	</div>
	<div class="pull-right">
		<input id="checkout-to-payment-btn" type="submit" class="btn btn-success" value="Proceed to Secure Payment"/>
	</div>
	<?php endif; ?>

</form>

<iframe name="checkout_target"></iframe>
</div>

<?php require_once 'footer.php'; ?>