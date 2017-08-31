<?php
class Homedao extends CI_Model {

	/**
	 *
	 */
	function which( $host ) {
		$store = $this->db->query( "SELECT * FROM store WHERE host='".$host."'" )->row_array();
		if( !empty( $store ) )
			return $store[ 'id' ];

		return false;
	}

	/**
	 *
	 */
	function get_setting( $store_id, $setting ) {
		$res = $this->db->query( "SELECT * FROM settings WHERE name='".$setting."' AND store_id='".$store_id."'" )->row_array();
		if( !empty( $res ) )
			return $res[ 'value' ];

		return false;
	}
}