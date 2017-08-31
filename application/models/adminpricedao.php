<?php
class Adminpricedao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO sales_price(sku, customer_group, price, price_type) VALUES ";
		$this->_delete_instr = "DELETE FROM sales_price WHERE (sku,customer_group) IN (";

		$this->_query = $this->_insert_instr;
		$this->_update_query_arr = array();
		$this->_delete_query = $this->_delete_instr;


		$this->_temp_sku = 
		$this->_temp_customer_group = 
		$this->_temp_price = 
		$this->_temp_price_type = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'sales_price'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE sales_price RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE sales_price SELECT * FROM {$this->_table_name} WHERE 1=0" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return
				$this->db->query( "DROP TABLE {$this->_table_name}" ) &&
				$this->db->query( "ALTER TABLE sales_price ADD INDEX sales_price(customer_group,sku)" );
	}

	/**
	 *
	 */
	function additional_treatment() {}

	function add_sku( $sku ) {
		$this->_temp_sku.= $sku;
	}

	function add_customer_group( $customer_group ) {
		$this->_temp_customer_group.= $customer_group;
	}

	function add_price( $price ) {
		$this->_temp_price.= $price;
	}

	function add_price_type( $price_type ) {
		$this->_temp_price_type.= $price_type;
	}

	/**
	 *
	 */
	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE sales_price FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' (sku, customer_group, price, price_type)" );
	}

	/**
	 * 
	 */
	function run_query( $type ) {
		$temp = "('{$this->_temp_sku}', '{$this->_temp_customer_group}', '{$this->_temp_price}', '{$this->_temp_price_type}'),";


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
			$temp_del = "('{$this->_temp_sku}','{$this->_temp_customer_group}'),";
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
		$this->_temp_customer_group = 
		$this->_temp_price = 
		$this->_temp_price_type = '';
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