<?php
class Adminstockdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO stock(sku, qty, due_date) VALUES ";
		$this->_delete_instr = "DELETE FROM stock WHERE sku IN (";

		$this->_query = $this->_insert_instr;
		$this->_update_query_arr = array();
		$this->_delete_query = $this->_delete_instr;

		$this->_temp_sku = 
		$this->_temp_qty =
		$this->_temp_due_date = '';
	}

	/**
	 *
	 */
	function backup( $type, $bot = false ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'stock'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE stock RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE stock SELECT * FROM {$this->_table_name} WHERE 1=0" ) &&
				$this->db->query( "ALTER TABLE stock CHANGE sku sku VARCHAR(100) PRIMARY KEY" );
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

	function add_item_no( $sku ) {
		$this->_temp_sku.= $sku;
	}

	function add_free_stock( $qty ) {
		$this->_temp_qty.= $qty;
	}

	function add_due_date( $date ) {
		$this->_temp_due_date.= $date;
	}

	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE stock FIELDS ENCLOSED BY '\"' TERMINATED BY ';' LINES TERMINATED BY '\r\n'(sku, qty, due_date)" );
	}

	/**
	 * 
	 */
	function run_query( $type ) {
		$temp = "('{$this->_temp_sku}', '{$this->_temp_qty}', '{$this->_temp_due_date}'),";


		// Bulk insert on "rebuild"
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$q = $this->_query.$temp;

			// Run the query when its length ~ 4 Kb. It's far from the max_allowed_packet limit but is used for safe
			if( strlen( $q ) >= 4096 ) {
				$this->db->query( rtrim( $this->_query, ',' ) );

				$this->_query = $this->_insert_instr.$temp;
			}
			else
				$this->_query = $q;
		}
		elseif( $type == 'update' ) {
			$temp_del = "'{$this->_temp_sku}',";
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

		$this->_temp_sku = 
		$this->_temp_qty =
		$this->_temp_due_date = '';
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