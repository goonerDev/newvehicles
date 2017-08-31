<?php
$out = '';
foreach( $models[ 'data' ] as $m )
	$out .= ',{"name":"'.misc::esc_json( ucwords( strtolower( $m[ 'model' ] ) ) ).'"}';

echo '['.substr( $out, 1 ).']';