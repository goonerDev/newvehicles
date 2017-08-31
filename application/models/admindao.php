<?php
class Admindao extends CI_Model {

	var $_query;
	var $_total;

	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_query = '';
		$this->_total = 0;
	}

	function _insert_csv( $filename ) {}

	/**
	 * As it always remains a non-treated query execute it
	 */
	function run_the_remain_query( $type = '' ) {
		$qry = rtrim( $this->_query, ',' );
		if( !empty( $qry ) )
			$this->db->query( $qry );
	}

	/**
	 * Get the last id from which starts the next export
	 */
	function get_last_export_id( $table_name, $store_id ) {
		$res = $this->db->query( "SELECT last_id FROM export_meta WHERE table_name='".$table_name."' AND store_id='".$store_id."'" )->row_array();
		return @$res[ 'last_id' ];
	}

	/**
	 * Set the last id from which ends the current export
	 */
	function set_last_export_id( $table_name, $last_id, $store_id ) {
		return $this->db->query( "INSERT INTO export_meta SET table_name='".$table_name."', last_id='".$last_id."', store_id='".$store_id."' ON DUPLICATE KEY UPDATE last_id='".$last_id."'" );
	}
}