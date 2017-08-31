<?php foreach( $data as $m ): ?>
<option value="<?php echo misc::esc_attr( strtolower( misc::urlencode( str_replace( '-', '', $m[ 'model' ] ) ) ) ); ?>"><?php echo ucwords( strtolower( $m[ 'model' ] ) ); ?></option>
<?php endforeach;