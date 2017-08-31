<?php
class AddressDao extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 *
	 */
	function delivery( $customer_id ) {
		return $this->db->query( "SELECT * FROM address WHERE customer_id='$customer_id' AND by_default=1 ORDER BY id" )->row_array();
	}

	/**
	 *
	 */
	function read( $id ) {
		return $this->db->query( "SELECT * FROM address WHERE id='$id'" )->row_array();
	}
}