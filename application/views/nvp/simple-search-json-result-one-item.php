<?php //echo count($result); die;
if(is_array($result)){
     $res = '[';
    foreach ($result as $value) {
        $res .= '{';
        $res.= '"url":"'.base_url().strtolower( misc::urlencode( $value[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $value[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $value[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $value[ 'sku' ] ) ) ).'-'.strtolower( url_title( $value[ 'product' ] ) ).'",';
        $res.= '"text":"'.$value[ 'manufacture' ].' - '.$value[ 'model_type' ].' - '.$value[ 'sku' ].'"';
         $res.= '},';
    }
    $res=rtrim($res, ",");
    $res.= ']';
    
}  else {
 $res = '[{';
$res.= '"url":"'.base_url().strtolower( misc::urlencode( $result[ 'manufacture' ] ) ).'/'.strtolower( misc::urlencode( $result[ 'model' ] ) ).'/'.strtolower( misc::urlencode( $result[ 'model_type' ] ) ).'/'.strtolower( misc::urlencode( str_replace( '-', '', $result[ 'sku' ] ) ) ).'-'.strtolower( url_title( $result[ 'product' ] ) ).'",';
$res.= '"text":"'.$result[ 'manufacture' ].' - '.$result[ 'model_type' ].' - '.$result[ 'sku' ].'"';
$res.= '}]';
}


die( $res );