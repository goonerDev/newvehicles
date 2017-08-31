<?php
class Adminorderstatusdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO order_tracking(order_no, status) VALUES ";
		$this->_delete_instr = "DELETE FROM order_tracking WHERE (order_no) IN (";

		$this->_query = $this->_insert_instr;
		$this->_update_query_arr = array();
		$this->_delete_query = $this->_delete_instr;


		$this->_temp_order_no = 
		$this->_temp_status = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'order_tracking'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE order_tracking RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE order_tracking SELECT * FROM {$this->_table_name} WHERE 1=0" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return $this->db->query( "DROP TABLE {$this->_table_name}" );
	}

	/**
	 *
	 */
	function additional_treatment() {}

	function add_order_no( $order_no ) {
		$this->_temp_order_no.= $order_no;
	}

	function add_status( $status ) {
		$this->_temp_status.= $status;
	}

	/**
	 *
	 */
	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE order_tracking FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' (order_no, status)" );
	}

	/**
	 * 
	 */
	function run_query( $type ) {
		$temp = "('{$this->_temp_order_no}', '{$this->_temp_status}'),";


		// Bulk insert on "rebuild"
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {

			$q = $this->_query.$temp;

			// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
			if( strlen( $q ) >= 4096 ) {
				$this->db->query( rtrim( $this->_query, ',' ) );
				unset( $this->_query );
				$this->_query = $this->_insert_instr.$temp;
			}
			else
				$this->_query = $q;

		}
		elseif( $type == 'update' ) {
			$temp_del = "('{$this->_temp_order_no}'),";
			$q = $this->_delete_query.$temp_del;
			if( strlen( $q ) >= 4096 ) {
				$this->db->query( rtrim( $this->_delete_query, ',' ).')' );
				$this->_delete_query = $this->_delete_instr.$temp_del;
			}
			else
				$this->_delete_query = $q;

			$q = $this->_query.$temp;
			if( strlen( $q ) >= 4096 ) {
				$this->_update_query_arr[] = rtrim( $this->_query, ',' );
				$this->_query = $this->_insert_instr.$temp;
			}
			else
				$this->_query = $q;
		}

		$this->_temp_order_no = 
		$this->_temp_status = '';
	}

	/**
	 *
	 */
	function run_the_remain_query( $type = '' ) {
		if( $type == 'update' ) {
			if( $this->_delete_query != $this->_delete_instr )
				$this->db->query( rtrim( $this->_delete_query, ',' ).')' );

			foreach( $this->_update_query_arr as $q )
				$this->db->query( $q );
		}

		if( !empty( $this->_query ) && $this->_query != $this->_insert_instr )
			return $this->db->query( rtrim( $this->_query, ',' ) );

		return true;
	}
}