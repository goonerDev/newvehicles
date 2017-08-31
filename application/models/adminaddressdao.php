<?php
class Adminaddressdao extends Admindao {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->_insert_instr = "INSERT INTO address(customer_id, recipient, address, town, county, postcode, by_default) VALUES ";
		$this->_delete_instr = "DELETE FROM address WHERE customer_id IN (";

		$this->_query = $this->_insert_instr;
		$this->_update_query_arr = array();
		$this->_delete_query = $this->_delete_instr;

		$this->_temp_id = 
		$this->_temp_customer_id = 
		$this->_temp_recipient = 
		$this->_temp_address = 
		$this->_temp_town = 
		$this->_temp_county = 
		$this->_temp_post_code = 
		$this->_temp_by_default = '';
	}

	/**
	 *
	 */
	function backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' ) {
			$this->_table_name = 'address'.date( 'YmdHis' );

			return
				$this->db->query( "ALTER TABLE address RENAME TO {$this->_table_name}" ) &&
				$this->db->query( "CREATE TABLE address SELECT * FROM {$this->_table_name} WHERE 1=0" ) &&
				$this->db->query( "ALTER TABLE address CHANGE id id INT PRIMARY KEY AUTO_INCREMENT" );
		}
	}

	/**
	 *
	 */
	function drop_backup( $type ) {
		if( $type == 'rebuild-daily' || $type == 'rebuild-weekly' )
			return
				$this->db->query( "DROP TABLE {$this->_table_name}" );
	}

	/**
	 *
	 */
	function additional_treatment() {}

	function add_id( $id ) {
		$this->_temp_id.= $id;
	}

	function add_customer_id( $customer_id ) {
		$this->_temp_customer_id.= $customer_id;
	}

	function add_recipient( $recipient ) {
		$this->_temp_recipient.= $recipient;
	}

	function add_address( $address ) {
		$this->_temp_address.= $address;
	}

	function add_town( $town ) {
		$this->_temp_town.= $town;
	}

	function add_county( $county ) {
		$this->_temp_county.= $county;
	}

	function add_post_code( $post_code ) {
		$this->_temp_post_code.= $post_code;
	}

	function add_by_default( $default ) {
		$this->_temp_by_default.= $default;
	}

	function _insert_csv( $filename ) {
		$this->db->query( "LOAD DATA LOCAL INFILE '$filename' REPLACE INTO TABLE address FIELDS ENCLOSED BY '\"' TERMINATED BY ';' LINES TERMINATED BY '\r\n'(customer_id, recipient, address, town, county, postcode, by_default)" );
	}

	/**
	 * Action on addresses consists of inserting only
	 */
	function run_query( $type ) {
		$temp = "('{$this->_temp_customer_id}', '{$this->_temp_recipient}', '{$this->_temp_address}', '{$this->_temp_town}', '{$this->_temp_county}', '{$this->_temp_post_code}', '{$this->_temp_by_default}'),";


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
			$temp_del = "'{$this->_temp_customer_id}',";
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
		elseif( $type == 'insert' ) {
			$q = $this->_insert_instr.rtrim( $temp, ',' );
			$this->db->query( $q );

			$this->_query = '';
		}

		$this->_temp_id =
		$this->_temp_customer_id =
		$this->_temp_recipient =
		$this->_temp_address =
		$this->_temp_town =
		$this->_temp_county =
		$this->_temp_post_code =
		$this->_temp_by_default = '';
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