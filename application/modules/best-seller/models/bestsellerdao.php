<?php
class Bestsellerdao extends CI_Model {
	/**
	 * Display best-sellers. The items should have prices and be in stock
	 */
	function random( $limit, $customer_group, $user_no, $vat = 0 ) {
		$required = ( int ) $limit;

		$qry = "SELECT c.id FROM catalog c ";
		$qry.= "INNER JOIN best_seller b ON b.sku=c.sku AND b.model_type=c.model_type ";
		$qry.= "INNER JOIN stock s ON s.sku=c.sku ";
		$qry.= "WHERE s.qty>0 ORDER BY RAND()";
		$res = $this->db->query( $qry )->result_array();

		$final_result = array();

		for( $i = 0, $n = count( $res ); $i < $n && 0 < $required; $i += $limit ) {
			// The previous query ensures the stock quantity is greater than 0 than set it to 1 in the next query to mean it is not
			// 0. Knowing the exact value is not necessary in best-seller
			$qry = "SELECT manufacture, model, model_type, sku, product, product_image, certificate, ebay_carriage, 1 stock ";

			$qry.= empty( $customer_group ) ? ', 0 price' : ",(SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|') FROM sales_price WHERE sku=c.sku AND (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."') OR ('".$user_no."' LIKE '1%' AND customer_group='CHOUT'))) price ";

			$qry.= "FROM catalog c ";

			$ids = '';
			for( $j = $i, $l = 0; $l < $limit; $j++, $l++ )
				$ids.= ','.$res[ $j ][ 'id' ];

			$qry.= "WHERE id IN (".substr( $ids, 1 ).")";

			$res2 = $this->db->query( $qry )->result_array();

			// Check if the retrieved products are all have price. For those which have put them to the final result
			for( $k = 0, $m = count( $res2 ); $k < $m; $k++ ) {
				$prices = misc::get_available_prices( $res2[ $k ][ 'price' ], $user_no, $vat );
				if( $prices[ 'price' ] > 0 ) {
					$final_result[] = $res2[ $k ];
					$required--;

					if( $required == 0 )
						break;
				}
			}
		}
		

		return $final_result;
	}

	/**
	 *
	 */
	function update_by_new_order( $store_id, $items ) {
		foreach( $items as $item )
			if( $item[ 'sku' ] != 'zcarriage' ) {
				$this->db->query( "INSERT INTO best_seller VALUES ('".misc::sanitize( $item[ 'model_type' ] )."', '".$item[ 'sku' ]."', '".$item[ 'qty' ]."', '".$store_id."') ON DUPLICATE KEY UPDATE nb=nb+'".$item[ 'qty' ]."'" );
			}
	}
}