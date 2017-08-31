<?php require_once 'header.php'; ?>

<div class="content-section">
<?php /* Title */ ?>
	<fieldset class="">
	<legend>Order status</legend>
	<div class="col-xs-12">
		<label class="col-xs-12"><?php //echo $status; ?>Processed</label>
	</div>
	</fieldset> 

	<fieldset class="">
	<legend>Order Summary</legend>

	<div class="col-xs-12">
		<label class="col-xs-12">Order ID: <?php echo $cart_details[ 'order_id' ]; ?></label>
		<label class="col-xs-12">Recipient: <?php echo $cart_details[ 'recipient' ]; ?></label>
		<label class="col-xs-12">Payment method: <?php echo $cart_details[ 'payment_method' ]; ?></label>
		<label class="col-xs-12">Date: <?php echo $cart_details[ 'creation_date' ]; ?></label>
	</div>

	<table id="items" class="table table-striped">

		<thead>
			<tr>
				<th width="25"></th>
				<th width="400">Product</th>
				<th width="150">Unit price( <?php echo CURRENCY; ?>)</th>
				<th width="150">Quantity</th>
				<th width="100">Total( <?php echo CURRENCY; ?>)</th>
			</tr>
		</thead>

		<tbody>

		<?php
		$i = 1;
		$total = 0;
		foreach( $items as $item ):
			if( strtolower( $item[ 'sku' ] == 'zcarriage' ) )
				continue;

		$ss_total = number_format( $item[ 'qty' ] * $item[ 'pu' ], 2, '.', ' ' );
		$total += $ss_total;
		?>

			<tr>
<!-- Order -->
				<td><?php echo $i; ?></td>
<!-- Product name -->
				<td><?php echo '<strong>'.$item[ 'product' ].'</strong> - '.$item[ 'model_type' ]; ?></td>
<!-- Unit price -->
				<td class="center-aligned"><?php echo number_format( $item[ 'pu' ], 2 ); ?></td>
<!-- Qty input -->
				<td class="center-aligned"><?php echo $item[ 'qty' ]; ?></td>
<!-- Total -->
				<td class="center-aligned"><?php echo $ss_total; ?></td>

			</tr>

		<?php $i++; endforeach; ?>
		<?php if( $shipment_rate > 0 ): $shipment_rate = number_format( $shipment_rate, 2 ); ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><strong>Shipment rate</strong></td>
				<td><?php echo $shipment_rate; ?></td>
				<td>1</td>
				<td><?php echo $shipment_rate; ?></td>
			</tr>
		<?php $total += $shipment_rate; ?>
		<?php endif; ?>

			<tr>
				<td colspan="3"></td>
				<td><strong>TOTAL</strong></td>
				<td><strong><?php echo $total; ?></strong></td>
			</tr>
		</tbody>
	</table>
	</fieldset>
</div>

<?php require_once 'footer.php';