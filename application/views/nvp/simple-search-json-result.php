<?php
if( !empty( $result[ 'data' ] ) && count( $result[ 'data' ] ) > 0 ):

$res = '';

$array_pattern = explode( ' ', $pattern );
$n = count( $array_pattern );

foreach( $result[ 'data' ] as $line ) {

	$res.= ',{';

	$catalog_level = 0;
	$displayable_text = array();
	for( $i = 0; $i < $n; $i++ ) {
		if( empty( $array_pattern[ $i ] ) )
			continue;

		if( stristr( $line[ 'manufacture' ], $array_pattern[ $i ] ) ) {
			$displayable_text[ 'manufacture' ] = $line[ 'manufacture' ];
			$catalog_level = $catalog_level > 1 ? $catalog_level : 1;
		}
		elseif( stristr( $line[ 'model' ], $array_pattern[ $i ] ) ) {
			$displayable_text[ 'model' ] = $line[ 'model' ];
			$catalog_level = $catalog_level > 2 ? $catalog_level : 2;
		}
		elseif( stristr( $line[ 'model_type' ], $array_pattern[ $i ] ) ) {
			$displayable_text[ 'model_type' ] = $line[ 'model_type' ];
			$catalog_level = $catalog_level > 3 ? $catalog_level : 3;
		}
		elseif( stristr( $line[ 'product' ], $array_pattern[ $i ] ) ) {
			$displayable_text[ 'product' ] = $line[ 'product' ];
			$catalog_level = $catalog_level > 4 ? $catalog_level : 4;
		}
	}

	if( $catalog_level != 0 ) {
		switch( $catalog_level ) {
			case 1:
				$res.= '"url":"'.BASE_URL.strtolower( misc::urlencode( $line[ 'manufacture' ] ) ).'",';
				break;
			case 2:
				$res.= '"url":"'.BASE_URL.strtolower( misc::urlencode( $line[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model' ] ) ).'",';
				break;
			case 3:
				$res.= '"url":"'.BASE_URL.strtolower( misc::urlencode( $line[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'",';
				break;
			case 4:
				$res.= '"url":"'.BASE_URL.strtolower( misc::urlencode( $line[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $line[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $line[ 'sku' ] ) ) ).'-'.strtolower( url_title( $line[ 'product' ] ) ).'",';
				break;
		}

		$res.= '"text":"';

		if( empty( $displayable_text[ 'manufacture' ] ) && empty( $displayable_text[ 'model' ] ) && empty( $displayable_text[ 'model_type' ] ) )
			$res.= misc::esc_json( $displayable_text[ 'product' ].' - '.$line[ 'model_type' ] );
		else
			$res.= implode( ' - ', $displayable_text );

		$res.= '"';
	}

	$res.= '}';
	
}

echo '['.substr( $res, 1 ).']';

endif; ?>