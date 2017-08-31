<?php
class Adminorderdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Get all orders matching the filter
	 * @param cart_id ID of the order. If provided all other arguments are ignored
	 * @param last_id From which order starts the export. This value is not the cart ID, it is the primary key (incremented value) of order table
	 * @param nb How many results to return
	 */
	function get_from_last_export( $params ) {
		extract( $params );

		$where = " WHERE (SELECT id FROM cart_items WHERE cart_id=o.cart LIMIT 1) IS NOT NULL AND c.store_id='".$store_id."'";
		$limit = "";

		if( $order_id != 0 )
			$where.= " AND o.id='".$order_id."'";
		else {
			$where.= " AND o.id>='".$last_id."'";

			$limit = $nb == 0 ? "" : " LIMIT ".$nb;
		}

		return $this->db->query( "SELECT c.*,o.id order_id FROM cart c INNER JOIN orders o ON c.id=o.cart AND c.customer_id=o.customer_id".$where." ".$limit )->result_array();
	}

	/**
	 *
	 */
	function set_last_export_id( $last_id, $store_id ) {
		parent::set_last_export_id( 'order', $last_id, $store_id );
	}
}