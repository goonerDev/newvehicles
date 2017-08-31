<?php require_once 'header.php'; ?>
<img src="http://www.newvehicleparts.co.uk/assets/nvp/images/GlobalSign-Trust-Seal.png" style="width:150px"  class="pull-right"/>
<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">

	<span class="color-171f6a">Checkout</span>

</h1>
<p><strong><span style="color:red;">Any orders placed to countries other than United Kingdom, Isle of Man, Channel  Islands, Ireland &amp; Isle of Scilly will NOT be processed, if in doubt call +44 (0) 1405 810 876</span</strong></p>
<div id="cart-checkout-form">

<div id="alert-msg"><?php echo @$err; ?></div>

<div class="col-xs-12">
	<h2>Your order details</h2>
	<?php if( empty( $items ) ): ?>
		<p class="alert alert-danger"><?php echo E_EMPTY_CART; ?></p>
	<?php else: ?>
		<table id="items" class="table table-striped">
			<thead>
				<tr>
					<th width="50%">Product</th>
					<th width="20%" class="numeric-align">Price(<?php echo str_replace( '&nbsp;', '', CURRENCY ); ?>)</th>
					<th width="20px">Qty</th>
					<th width="20%" class="numeric-align">Total(<?php echo str_replace( '&nbsp;', '', CURRENCY ); ?>)</th>
				</tr>
			</thead>
			<tbody>
	<?php
	foreach( $items as $item ):
		if( strtolower( $item[ 'sku' ] == 'zcarriage' ) )
			continue;

		$total = number_format( $item[ 'qty' ] * $item[ 'pu' ], 2, '.', ' ' ); ?>

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
					<td>
					<div id="tt-<?php echo $item[ 'id' ]; ?>" class="numeric-align">
						<?php echo $total; ?>
					</div>
					<div class="numeric-align">
						<a href="<?php echo BASE_URL; ?>delete-item/<?php echo $item[ 'id' ]; ?>"><span class="glyphicon glyphicon-trash"></span></a>
					</div>
					</td>
				</tr>
	<?php endforeach; ?>
				<tr>
					<td id="sub-total-shipping-msg" colspan="3">Shipping</td>
					<td id="sub-total-shipping-rate" class="numeric-align"><?php echo $shipment_rate == 0 ? '<span style="color:#fc0202">postcode required</span>' : number_format( $shipment_rate, 2 ); ?></td>
				</tr>
				<tr>
					<td colspan="3"><strong>TOTAL</strong></td>
					<td id="total" class="numeric-align"></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
	<?php endif; ?>
</div>

<br class="clear"/>

<form name="checkout_form" method="post" action="<?php echo BASE_URL; ?>checkout" target="checkout_target"  class="clearfix">
	<div class="col-xs-12">
		<h2>Your details</h2>
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

		<div class="col-xs-12">
			<label for="">Address <span class="required-field">*</span></label>
			<input type="text" name="address" value="<?php echo misc::esc_attr( @$cart_details[ 'address' ] ); ?>" class="form-control" data-tab-index="5"/>
		</div>

		<div class="col-xs-12">
			<div class="col-sm-6 col-xs-12">
				<label for="">City <span class="required-field">*</span></label>
				<input type="text" name="town" value="<?php echo misc::esc_attr( @$cart_details[ 'town' ] ); ?>" class="form-control" data-tab-index="6"/>
			</div>
		<div class="col-sm-6 col-xs-12 no-padding-right">
			<label for="">Postcode <span class="required-field">*</span></label>
			<input type="text" name="postcode" value="<?php echo misc::esc_attr( @$cart_details[ 'postcode' ] ); ?>" class="form-control" data-tab-index="7"/>
		</div>
		</div>

		<div class="col-xs-12">
			<label for="">Country <span class="required-field">*</span></label>
			<select name="country" class="form-control" data-tab-index="8">
			<option value="">-- Select County / Country --</option>

				<option value="Isle of Wight"<?php echo @$cart_details[ 'county' ] == 'Isle of Wight'  ? ' SELECTED' : ''; ?>>Isle of Wight</option>
				<option value="Isle of Man"<?php echo @$cart_details[ 'county' ] == 'Isle of Man'  ? ' SELECTED' : ''; ?>>Isle of Man</option>
				<option value="Channel Islands"<?php echo @$cart_details[ 'county' ] == 'Channel Islands'  ? ' SELECTED' : ''; ?>>Channel Islands</option>
				<option value="Northern Ireland"<?php echo @$cart_details[ 'county' ] == 'Northern Ireland'  ? ' SELECTED' : ''; ?>>Northern Ireland</option>
				<option value="Southern Ireland"<?php echo @$cart_details[ 'county' ] == 'Southern Ireland'  ? ' SELECTED' : ''; ?>>Southern Ireland</option>
				<option value="Isles of Scilly"<?php echo @$cart_details[ 'county' ] == 'Isles of Scilly'  ? ' SELECTED' : ''; ?>>Isles of Scilly</option>
				<option value="United Kingdom"<?php echo @$cart_details[ 'county' ] == 'United Kingdom'  ? ' SELECTED' : ''; ?>>United Kingdom</option>

			</select>
		</div>

	</div>
<input type="hidden" name="payment_method" value="Paypal mobile"/>
<input type="hidden" name="ajaxCall" value="ajax"/>
	<?php if( !empty( $items ) ): ?>

	<div class="col-xs-12">
		<h2>Delivery Notes:</h2>
		<textarea id="checkout-page-comments" name="comments" class="col-xs-12" rows="3" data-tab-index="11"><?php echo @$cart_details[ 'comments' ]; ?></textarea>
	</div>

	<div class="col-xs-12">
		
		<input id="checkout-to-payment-btn" type="submit" class="btn btn-success" value="Proceed to Secure Payment"/>
		<a id="checkout-continue-shopping-btn" href="javascript:history.go(-1)" class="btn btn-primary">Continue shopping</a>
	</div>

	<?php endif; ?>
	
</form>
<iframe name="checkout_target"></iframe>
</div>

<?php require_once 'footer.php'; ?>