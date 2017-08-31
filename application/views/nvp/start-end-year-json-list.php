<?php
$out = '';
foreach( $data as $s )
	$out .= ',{"start_end_year":"'.misc::esc_json( str_replace( array( $model, $manufacture ), '', ucwords( strtolower( $s[ 'model_type' ] ) ) ) ).'"}';

echo '['.substr( $out, 1 ).']';
