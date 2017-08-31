<?php
class CartDao extends CI_Model {
	/**
	 * @params user_id - vrm
	 */
	function create( $params ) {
		extract( $params );

		$this->db->query( "INSERT INTO cart SET customer_id='".$user_id."', email='".$email."', store_id='".$store_id."', creation_date=DATE_FORMAT(NOW(),'%d-%m-%Y %H:%i:%s'), vrm='".$vrm."'" );
		$res = $this->db->query( "SELECT LAST_INSERT_ID() id" )->row_array();
		return $res[ 'id' ];
	}

	/**
	 * read a cart details
	 *
	 */
	function read( $params ) {
		extract( $params );

		return $this->db->query( "SELECT c.*, o.id order_id FROM cart c LEFT OUTER JOIN orders o ON o.cart=c.id WHERE c.id='".$cart_id."' AND c.customer_id='".$user_no."'" )->row_array();
	}

	/**
	 * @param array $params user_id - cart_id
	 */
	function discard( $params ) {
		extract( $params );

		$items = $this->items( $params );

		if( $this->db->query( "DELETE FROM cart WHERE id='".$cart_id."' AND customer_id='".$user_no."'" ) ) {
			if( is_array( $items ) )
				foreach( $items as $item )
					$this->cartdao->delete_item( array( 'user_no' => $user_no, 'id' => $item[ 'id' ] ) );
		}
	}

	/**
	 *
	 */
	function set_real_user_no( $cart_id, $user_no, $real_user_no ) {
		$this->db->query( "UPDATE cart SET customer_id='".$real_user_no."' WHERE customer_id='".$user_no."' AND id='".$cart_id."'" );
		$this->db->query( "UPDATE cart_items SET user='".$real_user_no."' WHERE user='".$user_no."' AND cart_id='".$cart_id."'" );
	}

	/**
	 * Add new item to the cart. If it already exists then overwrite
	 * Update also the stock quantity
	 * @params cart_id - item_id - qty - customer_group - user - price - quote_no - vat
	 */
	function add_item( $params ) {
		extract( $params );

		if( empty( $cart_id ) )
			return false;

		$this->db->query( "LOCK TABLES cart_items WRITE, sales_price WRITE, stock WRITE" );

		// In case of quote, no need to look up price in sales_price table, it's already provided

		// There are 3 sort of prices: customer price group, customer price, bodyshop. In cart the first 2 are taken into account.
		// The latter is to indicate to bodyshop what would be the price when reselling the item. Bodyshop prices are known by
		// customer_group CHOUT, customer override is by the user_no else customer group.
		if( !empty( $price ) )
			$prices = array( 'price' => $price, 'charge_out' => 0 );
		else {
			// Retrieve the unit price of the product based on the user and customer group
			$res = $this->db->query( "SELECT GROUP_CONCAT(CONCAT(customer_group,';',price) SEPARATOR '|')price FROM sales_price WHERE sku='".$item_id."' AND (customer_group='".$user_no."' OR customer_group IN ('".$customer_group."') OR ('".$user_no."' LIKE '1%' AND customer_group='CHOUT'))" )->row_array();

			$prices = misc::get_available_prices( $res[ 'price' ], $user_no, $vat );
		}

		$quote_no = empty( $quote_no ) ? '' : $quote_no;
		// Apply the changes against the database
		$qry = "INSERT INTO cart_items SET cart_id='".$cart_id."', sku='".$item_id."', user='".$user_no."', qty='".$qty."', pu=".$prices[ 'price' ].", ebay_shipping='".$ebay_shipping."', creation_date=DATE_FORMAT(NOW(),'%Y%m%d'), quote_no='".$quote_no."' ON DUPLICATE KEY UPDATE qty=qty+'".$qty."', ebay_shipping='".$ebay_shipping."'";

		$this->db->query( $qry );
	
		// Update the qty of the stock
		// It's only executed when the order is not created from a quote
		if( $quote_no == '' )
			$this->db->query( "UPDATE stock SET qty=qty-".$qty." WHERE sku='".$item_id."'" );

		$this->db->query( "UNLOCK TABLES" );

		return $prices;
	}

	/**
	 * @params id - user_id
	 */
	function delete_item( $params ) {
		extract( $params );

		$this->db->query( "LOCK TABLES cart_items WRITE, stock WRITE" );

		// Deleting an item means putting back the previous qty to stock.

		$aqty = $this->db->query( "SELECT qty, sku, quote_no, cart_id FROM cart_items WHERE id='".$id."' AND user='".$user_no."'" )->row_array();

		if( empty( $aqty ) ) {
			$this->db->query( "UNLOCK TABLES" );

			return false;
		}

		// If quote_no is not empty it means the order item is picked from a quote so no need to update the stock quantity
		// otherwise do it
		if( empty( $aqty[ 'quote_no' ] ) )
			$this->db->query( "UPDATE stock SET qty=qty+{$aqty['qty']} WHERE sku='{$aqty['sku']}'" );

		$res = $this->db->query( "DELETE FROM cart_items WHERE id='".$id."' AND user='".$user_no."'" );

		// Is there any other items than the carriage?
		$a = $this->db->query( "SELECT COUNT(*)nb FROM cart_items WHERE user='".$user_no."' AND cart_id='".$aqty[ 'cart_id' ]."' AND sku<>'zcarriage'" )->row_array();
		// If not delete the shipping item
		if( $a[ 'nb' ] == 0 )
			$this->db->query( "DELETE FROM cart_items WHERE user='".$user_no."' AND cart_id='".$aqty[ 'cart_id' ]."'" );

		$this->db->query( "UNLOCK TABLES" );

		return $res;
	}

	/**
	 *
	 */
	function item_by_sku( $cart_id, $user_no, $sku ) {
		return $this->db->query( "SELECT * FROM cart_items WHERE cart_id='".$cart_id."' AND user='".$user_no."' AND sku='".$sku."'" )->row_array();
	}

	/**
	 *
	 */
	function item( $line_id ) {
		return $this->db->query( "SELECT * FROM cart_items WHERE id='".$line_id."'" )->row_array();
	}

	/**
	 * Get all products within the basket
	 * @param array $params cart_id - user_id - zcarriage
	 */
	function items( $params ) {
		extract( $params );

		// Should zcarriage be in the list of items or not? If $zcarriage='NO' exclude the zcarriage from the return result
		$zcarriage = !empty( $zcarriage ) && $zcarriage == 'NO' ? " AND ci.sku<>'zcarriage'" : '';

		$qry = "SELECT ci.*, product, product_image, model_type FROM cart ct INNER JOIN cart_items ci ON ct.id=ci.cart_id LEFT OUTER JOIN catalog c ON c.sku=ci.sku WHERE ct.id='".$cart_id."' AND user='".$user_no."' ".$zcarriage." AND ci.qty>0 GROUP BY ci.sku";
		return $this->db->query( $qry )->result_array();
	}

	/**
	 * Change the qty of an item in the basket
	 * @params id - item_id - qty
	 */
	function set_qty_item( $params ) {
		extract( $params );

		$this->db->query( "LOCK TABLES cart_items WRITE, stock WRITE" );

		// Changing the current qty means putting back first the previous qty to stock then removing the new one
		$aqty = $this->db->query( "SELECT qty, sku FROM cart_items WHERE id='".$id."'" )->row_array();
		$this->db->query( "UPDATE stock SET qty=qty+{$aqty['qty']}-".$qty." WHERE sku='{$aqty['sku']}'" );

		$res = $this->db->query( "UPDATE cart_items SET qty='".$qty."' WHERE id='".$id."' AND user='".$user_no."'" );

		$this->db->query( "UNLOCK TABLES" );
		return $res;
	}

	/**
	 * Get the total price of the items inside the basket
	 */
	function total( $params ) {
		extract( $params );

		// Should zcarriage be in the items or not? If $zcarriage='NO' exclude the zcarriage from the total cost
		$zcarriage = !empty( $zcarriage ) && $zcarriage == 'NO' ? " AND sku<>'zcarriage'" : '';

		// The pu already contains vat
		$res = $this->db->query( "SELECT SUM(ci.qty*ci.pu) price FROM cart_items ci INNER JOIN cart c ON c.id=ci.cart_id WHERE cart_id='".$cart_id."' AND user='".$user_no."' AND qty>0".$zcarriage )->row_array();
		return $res[ 'price' ];
	}

	/**
	 * Save the vrm
	 * @param array $params vrm - cart_id
	 */
	function save_vrm( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE cart SET vrm='".$vrm."' WHERE id='".$cart_id."'" );
	}

	/**
	 * 
	 */
	function save_your_reference( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE cart SET your_reference='".$your_reference."' WHERE id='".$cart_id."'" );
	}

	/**
	 *
	 */
	function get_current_shipping_flate_rate( $cart_id, $user_no ) {
		$res = $this->db->query( "SELECT * FROM cart_items WHERE sku='zcarriage' AND cart_id='".$cart_id."' AND user='".$user_no."'" )->row_array();
		return empty( $res ) ? false : $res;
	}

	/**
	 * Place the order
	 */
	function set_shipping_address( $params ) {
		extract( $params );

		return $this->db->query( "UPDATE cart SET recipient='".$recipient."', address='".$address."', town='".$town."', county='".$county."', postcode='".$postcode."', telephone='".$telephone."' WHERE id='".$cart_id."' AND customer_id='".$user_no."'" );
	}

	/**
	 *
	 */
	function remove_carriage_address( $params ) {
		extract( $params );

		return $this->db->query( "DELETE FROM cart_items WHERE cart_id='".$cart_id."' AND user='".$user_id."' AND sku='zcarriage'" );
	}

	/**
	 * @param array $params cart_id - user_id - transaction_id
	 */
	function mark_as_processed( $params ) {
		extract( $params );

		return $this->db->query( "INSERT INTO orders SET submitted_date=DATE_FORMAT(NOW(),'%d-%m-%Y %H:%i:%s'), cart='".$cart_id."', customer_id='".$user_no."', transaction_id='".$transaction_id."'" );
	}

	/**
	 * @param array $params it is the cart object
	 */
	function save_customer_to_final_order( $params ) {
		$params = misc::sanitize( $params );
		extract( $params );

		return $this->db->query( "UPDATE orders SET recipient='".$recipient."',email='".$email."',telephone='".$telephone."',address='".$address."',town='".$town."',postcode='".$postcode."',county='".$county."', payment_method='".$payment_method."' WHERE id='".$order_id."'" );
	}

	/**
	 *
	 */
	function set_order_status( $order_id, $status ) {
		return $this->db->query( "INSERT INTO order_tracking SET order_no='".$order_id."', status='".$status."', placed_date=DATE_FORMAT(NOW(),'%d-%m-%Y %H:%i:%s') ON DUPLICATE KEY UPDATE status='".$status."'" );
	}

	/**
	 *
	 */
	function get_order_status( $email, $order_id ) {
		$res = $this->db->query( "SELECT c.*, t.status FROM cart c INNER JOIN orders o ON o.cart=c.id INNER JOIN order_tracking t ON t.order_no=o.id WHERE o.id='".$order_id."' AND c.email='".$email."'" )->row_array();
		return empty( $res ) ? false : $res;
	}

	/**
	 * Check if the customer has registered quotes.
	 * There is no need to known how many quotes he has exactly.
	 */
	function has_quotes( $user_id ) {
		$res = $this->db->query( "SELECT quote_no FROM quotes WHERE acc_no='$user_id' LIMIT 1" )->row_array();
		return count( $res ) > 0;
	}

	/**
	 *
	 */
	function get_customer_details( $cart_id, $user_no ) {
		return $this->db->query( "SELECT * FROM cart WHERE id='".$cart_id."' AND customer_id='".$user_no."'" )->row_array();
	}

	/**
	 * Save customer details. Generally this is used when the site doesn't require login process then retrieve customer data
	 * on payment stage
	 * @param array $params firstname - lastname - email - telephone - address1 - address2 - city - postcode - country_code - payment_method
	 */
	function save_customer_details( $params ) {
		extract( $params );

		$qry = "UPDATE cart ".
			"SET customer_id='".$user_no."', ".
			"recipient='".$firstname." ".$lastname."', ".
			"email='".$email."', ".
			"address='".$address."', ".
			"town='".$city."', ".
			"postcode='".$postcode."', ".
			"county='".$country."' ";

		if( !empty( $telephone ) )
			$qry.=",telephone='".$telephone."' ";

		if( !empty( $payment_method ) )
			$qry.=",payment_method='".$payment_method."' ";

		if( !empty( $comments ) )
			$qry.=",comments='".$comments."' ";

		$qry.="WHERE id='".$cart_id."'";

		return $this->db->query( $qry );
	}

	/**
	 * Save customer details by providing field one by one
	 * @params array $params field_name - value - cart_id - user_no
	 */
	function save_individual_customer_detail( $params ) {
		extract( $params );

		$qry = "UPDATE cart SET ";
		$qry.= $fieldname."='".$value."' ";
		$qry.= "WHERE id='".$cart_id."' AND customer_id='".$user_no."'";

		return $this->db->query( $qry );
	}

	/**
	 *
	 */
	function add_shipping_rate_flate( $cart_id, $user_no, $rate ) {
		return $this->db->query( "INSERT INTO cart_items SET cart_id='".$cart_id."', sku='zcarriage', user='".$user_no."', qty=1, pu='".$rate."', creation_date=DATE_FORMAT(NOW(),'%Y%m%d') ON DUPLICATE KEY UPDATE pu='".$rate."'" );
	}

	/**
	 *
	 */
	function remove_carriage_address_item( $cart_id, $user_no ) {
		return $this->db->query( "DELETE FROM cart_items WHERE cart_id='".$cart_id."' AND user='".$user_no."' AND sku='zcarriage'" );
	}

	/**
	 *
	 */
	function save_payment_method( $cart_id, $user_no, $method ) {
		return $this->db->query( "UPDATE cart SET payment_method='".$method."' WHERE customer_id='".$user_no."' AND id='".$cart_id."'" );
	}
}