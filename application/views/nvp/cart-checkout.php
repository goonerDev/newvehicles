<?php require_once 'header-w-bar.php'; ?>

<div class="content-section">
<?php /* Title */ ?>
	<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">Cart Summary</h1>

	<?php if( !empty( $error ) ): ?>
	<p class="alert alert-danger"><?php echo $error; ?></p>
	<?php else: ?>
	<table id="items" class="table table-striped">

		<thead>
			<tr>
				<th width="25"></th>
				<th width="400">Product</th>
				<th width="150">Unit price( <?php echo CURRENCY; ?>)</th>
				<th width="150">Quantity</th>
				<th width="100">Total( <?php echo CURRENCY; ?>)</th>
				<th width="25"></th>
			</tr>
		</thead>

		<tbody>

		<?php
		$i = 1;
		foreach( $items as $item ):
			if( strtolower( $item[ 'sku' ] == 'zcarriage' ) )
				continue;

		$total = number_format( $item[ 'qty' ] * $item[ 'pu' ], 2, '.', ' ' ); ?>

			<tr>
<!-- Order -->
				<td><?php echo $i; ?></td>
<!-- Product name -->
				<td>
					<?php
						echo ( $item[ 'sku' ] == 'zcarriage' ? 'Carriage' : '<strong>'.$item[ 'product' ].'</strong> - '.$item[ 'model_type' ] );
					?>
				</td>
<!-- Unit price -->
				<td><input id="pu-<?php echo $item[ 'id' ]; ?>" type="text" value="<?php echo misc::esc_attr( number_format( $item[ 'pu' ], 2 ) ); ?>" disabled="disabled" size="5" class="center-aligned no-background no-border"/></td>
<!-- Qty input -->
				<td>
					<input id="input-<?php echo $item[ 'id' ]; ?>" type="text" value="<?php echo misc::esc_attr( $item[ 'qty' ] ); ?>" size="1" class="center-aligned"<?php echo ( $item[ 'sku' ] == 'zcarriage' ? ' readonly="readonly"' : '' ) ?>/>
					<a id="update-qty-<?php echo $item[ 'id' ]; ?>" href="#" title="Update" class="btn btn-default btn-xs">Update</a>
				</td>
<!-- Total -->
				<td><input id="tt-<?php echo $item[ 'id' ]; ?>" type="text" value="<?php echo misc::esc_attr( $total ); ?>" disabled="disabled" size="5" class="center-aligned no-background no-border"/></td>
<!-- Delete link -->
				<td><a href="<?php echo base_url(); ?>fcart/delete_item/<?php echo $item[ 'id' ]; ?>" title="Delete this item"><span class="glyphicon glyphicon-trash"></span></a></td>

			</tr>

		<?php $i++; endforeach; ?>

		</tbody>

	</table>

	<?php if( count( $items ) > 0 ): ?>

	<form class="form-horizontal" action="<?php echo base_url(); ?>submit-order" method="post">

		<!--<div class="form-group">
			<label class="control-label col-md-3">Your reference (Optional):</label>
			<div class="col-md-6">
				<input type="text" id="your_reference" name="your_reference" class="form-control" value="<?php echo @$cart_details[ 'your_reference' ]; ?>"/>
			</div>
		</div>-->

		<p align="center">

			<a href="<?php echo $referer; ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-chevron-left"></span> Continue Shopping</a>

			<a id="cart-discard-trigger" href="<?php echo base_url(); ?>fcart/discard" class="btn btn-danger pull-center"><span class="glyphicon glyphicon-trash"></span> Empty Cart</a>

			<button type="submit" class="btn btn-success pull-right">Continue to Checkout <span class="glyphicon glyphicon-chevron-right"></span></button>

		</p>

	</form>
	<?php endif; ?>
	<?php endif; ?>
-</div>

<?php require_once 'footer.php';