<?php
class OrderDao extends CI_Model {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Get all stored order
	 */
	function history( $params ) {
		extract( $params );

		$qry = "SELECT *,(SELECT SUM(qty*pu) FROM cart_items WHERE cart_id=cart.id)amount ";
		$qry.= "FROM cart ";
		$qry.= "WHERE customer_id='$user_id' ";
		$qry.= "ORDER BY creation_date DESC";
		return $this->db->query( $qry )->result_array();
	}
}