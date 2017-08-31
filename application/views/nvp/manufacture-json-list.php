<?php
$out = '';
foreach( $manufactures as $m )
	$out .= ',{"name":"'.misc::esc_json( $m[ 'manufacture' ] ).'"}';

echo '['.substr( $out, 1 ).']';