<?php if( isset( $data ) ): ?>
<?php 	foreach( $data as $y ): ?>
<?php 		$start_end_year = str_replace( array( $model, $manufacture ), '', $y[ 'model_type' ] ); ?>
<option value="<?php echo misc::esc_attr( strtolower( misc::urlencode( $y[ 'model_type' ] ) ) ); ?>"><?php echo htmlentities( ucwords( strtolower( $start_end_year ) ) ); ?></option>
<?php endforeach; ?>
<?php endif;